<?php

namespace App;

use App\Events\ActionSequenceScheduleDeleted;
use App\Events\ActionSequenceScheduleUpdated;
use App\Traits\Uuids;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;

/**
 * Class ActionSequenceSchedule
 * @package App
 */
class ActionSequenceSchedule extends CiliatusModel
{

    use Uuids, Notifiable;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'runonce', 'starts_at', 'action_sequence_id'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'runonce' => 'boolean'
    ];

    /**
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'last_start_at', 'last_finished_at', 'next_start_not_before'];

    /**
     * @var array
     */
    protected $dispatchesEvents = [
        'updated' => ActionSequenceScheduleUpdated::class,
        'deleting' => ActionSequenceScheduleDeleted::class
    ];

    /**
     * @param $weekdays
     */
    public function updateWeekdays($weekdays)
    {
        foreach ($weekdays as $day=>$value) {
            $this->setProperty(
                'ActionSequenceScheduleProperty',
                $day,
                $value
            );
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function properties()
    {
        return $this->hasMany('App\Property', 'belongsTo_id')
                    ->where('belongsTo_type', 'ActionSequenceSchedule');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sequence()
    {
        return $this->belongsTo('App\ActionSequence', 'action_sequence_id', 'id');
    }

    /**
     * @return \Carbon\Carbon
     */
    public function startsToday()
    {
        $startsToday = Carbon::now();
        $time_hours_minutes = explode(':', $this->starts_at);
        $startsToday->hour = (int)($time_hours_minutes[0]);
        $startsToday->minute = (int)(isset($time_hours_minutes[1]) ? $time_hours_minutes[1] : '00');
        $startsToday->second = 0;

        return $startsToday;
    }

    /**
     *
     */
    public function start()
    {
        $this->last_start_at = Carbon::now();
        $this->save();

        $this->info('start',null, null, $this->sequence);
    }

    /**
     *
     */
    public function finish()
    {

        $this->info('finish',null, null, $this->sequence);

        RunningAction::where('action_sequence_schedule_id', $this->id)->delete();

        if ($this->sequence->runonce == true) {
            $this->sequence->delete();
        }
        elseif ($this->runonce == true) {
            $this->delete();
        }
        else {
            $this->last_finished_at = Carbon::now();
            $this->save();
        }
    }

    /**
     * @param Controlunit $controlunit
     * @return array|void
     */
    public static function createAndUpdateRunningActions(Controlunit $controlunit)
    {
        if (ActionSequence::stopped()) {
            return;
        }

        foreach (ActionSequenceSchedule::get() as $ass) {

            if (!$ass->shouldBeHandledBy($controlunit)) {
                continue;
            }

            if (!$ass->checkConsistency()) {
                continue;
            }

            if (!$ass->running() && !$ass->shouldBeRunning()) {
                continue;
            }

            if ($ass->shouldBeStarted()) {
                $ass->start();
            }

            $all_actions_finished = true;

            foreach ($ass->sequence->actions as $a) {
                $running_action = RunningAction::where('action_id', $a->id)
                    ->where('action_sequence_schedule_id', $ass->id)
                    ->first();

                /*
                 * Check whether the RunningAction
                 * ran long enough
                 */
                if (is_null($running_action)) {
                    if ($a->startConditionsMetForSchedule($ass, $controlunit)) {
                        $all_actions_finished = false;
                        RunningAction::create([
                            'action_id' => $a->id,
                            'action_sequence_schedule_id' => $ass->id,
                            'started_at' => Carbon::now()
                        ]);
                    }
                }
                elseif ($running_action->shouldBeStopped()) {
                    $running_action->stop();
                }
                else {
                    // Action still running
                    $all_actions_finished = false;
                }
            }

            if ($all_actions_finished) {
                $running_actions = RunningAction::where('action_sequence_schedule_id', $ass->id)->get();
                foreach ($running_actions as $ra) {
                    $ra->delete();
                }
                $ass->finish();
            }
        }
        
    }

    /**
     * @return bool
     */
    public function running()
    {
        return ($this->last_start_at > $this->last_finished_at
            || !is_null($this->last_start_at) && is_null($this->last_finished_at));
    }

    /**
     * Returns true if action sequence should run on a certain weekday
     * The $weekday parameter is a 0-based index starting on sunday
     *
     * @param $weekday
     * @return bool
     */
    public function runsOnWeekday($weekday)
    {
        $property = $this->property('ActionSequenceScheduleProperty', $weekday);

        return !is_null($property) && (bool)$property->value;
    }


    /**
     * @return bool
     */
    public function nextStartNotBeforeConditionMet()
    {
        return is_null($this->next_start_not_before) || $this->startsToday()->gt($this->next_start_not_before);
    }

    /**
     * Returns true if the start time conditions to start right now are met
     * @return bool
     */
    public function startTimeConditionMet()
    {
        $time_ok = $this->startsToday()->lt(Carbon::now());
        $last_finished_ok = is_null($this->last_finished_at) ||
                            !$this->last_finished_at->isToday();

        return $time_ok && $last_finished_ok;
    }

    /**
     * Returns true if the start time conditions to start some time today are met
     * @return bool
     */
    public function startTimeConditionTodayMet()
    {
        return $this->startTimeConditionMet() ||
               $this->startsToday()->gt(Carbon::now());
    }

    /**
     * Returns true if the action sequence will run today
     * This is true if
     * - the start time is lower than the current time
     * - if hasn't finished today
     * - it's not running already
     * - the next start property is not set or it is set to today
     * - the schedule is scheduled for the current weekday
     *
     * @return bool
     */
    public function willRunToday()
    {
        return $this->startTimeConditionTodayMet()
                && !$this->running()
                && $this->nextStartNotBeforeConditionMet()
                && $this->runsOnWeekday(Carbon::today()->dayOfWeek);
    }

    /**
     * Checks whether any of the sequences actions
     * can be started by this $controlunit.
     *
     * If not return false
     *
     * @param Controlunit $controlunit
     * @return bool
     * @throws \Exception
     */
    public function shouldBeHandledBy(Controlunit $controlunit)
    {
        if (is_null($this->sequence)) {
            $this->delete();
            return false;
        }

        foreach ($this->sequence->actions as $a) {
            if (!is_null($a->target_object()) && $a->target_object()->controlunit_id == $controlunit->id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns true if the sequence did not
     * run today but the start time has passed
     *
     * @return bool
     */
    public function shouldBeRunning()
    {
        return $this->startTimeConditionMet()
            && $this->nextStartNotBeforeConditionMet();
    }

    /**
     * Checks whether any of the sequences actions
     * can be started by this $controlunit.
     *
     * If not return false,
     * otherwise return result of shouldBeStarted
     *
     * @param Controlunit $controlunit
     * @return bool
     * @throws \Exception
     */
    public function shouldBeStartedBy(Controlunit $controlunit)
    {
        return $this->shouldBeHandledBy($controlunit) && $this->shouldBeStarted();
    }

    /**
     * Returns true if the sequence should be running
     * but is not running
     *
     * @return bool
     */
    public function shouldBeStarted()
    {
        return $this->shouldBeRunning() && !$this->running();
    }

    /**
     * Return true if the schedule has a valid sequence
     *
     * @return bool
     */
    public function checkConsistency()
    {
        return !is_null($this->sequence);
    }

    /**
     * @param $minutes
     * @return bool
     */
    public function isOverdue($minutes = 10)
    {
        $startsToday = Carbon::now();
        $startsToday->hour = explode(':', $this->starts_at)[0];
        $startsToday->minute = explode(':', $this->starts_at)[1];
        $startsToday->second = 0;

        return !$this->running()
            && !$this->ranToday()
            && $startsToday->addMinutes($minutes)->lt(Carbon::now())
            && (is_null($this->next_start_not_before) || $this->next_start_not_before->isToday());
    }

    /**
     * @return bool
     */
    public function ranToday()
    {
        return !is_null($this->last_finished_at) && $this->last_finished_at->isToday();
    }

    /**
     * @return string
     */
    public function icon()
    {
        return 'clock';
    }

    /**
     *
     */
    public function url()
    {
        return url('action_sequence_schedules/' . $this->id);
    }
}
