<?php

namespace App;

use App\Events\ActionSequenceScheduleDeleted;
use App\Events\ActionSequenceScheduleUpdated;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ActionSequenceSchedule
 * @package App
 */
class ActionSequenceSchedule extends CiliatusModel
{

    use Traits\Uuids;

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

    protected $casts = [
        'runonce' => 'boolean'
    ];


    /**
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'last_start_at', 'last_finished_at'];

    /**
     * @param array $attributes
     * @return CiliatusModel|ActionSequenceSchedule
     */
    public static function create(array $attributes = [])
    {
        $new = new ActionSequenceSchedule($attributes);
        $new->save();

        if ($new->startsToday()->lt(Carbon::now()->subMinutes(10))) {
            $new->last_start_at = Carbon::now();
            $new->last_finished_at = Carbon::now();
        }

        $new->save();
        return $new;
    }

    /**
     *
     */
    public function delete()
    {
        foreach (RunningAction::where('action_sequence_schedule_id', $this->id)->get() as $ra) {
            $ra->delete();
        }

        broadcast(new ActionSequenceScheduleDeleted($this->id));

        parent::delete();
    }

    /**
     * @param array $options
     * @return bool
     */
    public function save(array $options = [])
    {
        $return = parent::save($options);

        broadcast(new ActionSequenceScheduleUpdated($this));

        return $return;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function properties()
    {
        return $this->hasMany('App\Property', 'belongsTo_id')->where('belongsTo_type', 'ActionSequenceSchedule');
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
        $startsToday->hour = explode(':', $this->starts_at)[0];
        $startsToday->minute = explode(':', $this->starts_at)[1];
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

        Log::create([
            'target_type'   =>  explode('\\', get_class($this))[count(explode('\\', get_class($this)))-1],
            'target_id'     =>  $this->id,
            'associatedWith_type' => 'ActionSequence',
            'associatedWith_id' => $this->action_sequence_id,
            'action'        => 'start'
        ]);
    }

    /**
     *
     */
    public function finish()
    {

        Log::create([
            'target_type'   =>  explode('\\', get_class($this))[count(explode('\\', get_class($this)))-1],
            'target_id'     =>  $this->id,
            'associatedWith_type' => 'ActionSequence',
            'associatedWith_id' => $this->action_sequence_id,
            'action'        => 'finish'
        ]);

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
     * @TODO: Clean this up. It's horrible
     * @return bool
     */
    public function willRunToday()
    {
        return (
                    (
                          $this->startsToday()->lt(Carbon::now())
                          && (is_null($this->last_finished_at) || !$this->last_finished_at->isToday())
                    )
                    || $this->startsToday()->gt(Carbon::now())
                )
                && !$this->running();
    }

    /**
     * Checks whether any of the sequences actions
     * can be started by this $controlunit.
     *
     * If not return false
     *
     * @param Controlunit $controlunit
     * @return bool
     */
    public function shouldBeHandledBy(Controlunit $controlunit)
    {
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
        return $this->startsToday()->lt(Carbon::now())
            && (is_null($this->last_finished_at) || !$this->last_finished_at->isToday());
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

        return !$this->running() && !$this->ranToday() && $startsToday->addMinutes($minutes)->lt(Carbon::now());
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
        return 'schedule';
    }

    /**
     *
     */
    public function url()
    {
        return url('action_sequence_schedules/' . $this->id);
    }
}
