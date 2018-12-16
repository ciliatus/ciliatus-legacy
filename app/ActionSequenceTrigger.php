<?php

namespace App;

use App\Events\ActionSequenceTriggerDeleted;
use App\Events\ActionSequenceTriggerUpdated;
use App\Traits\Uuids;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;

/**
 * Class ActionSequenceTrigger
 * @package App
 */
class ActionSequenceTrigger extends CiliatusModel
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
        'name', 'action_sequence_id', 'logical_sensor_id', 'reference_value', 'minimum_timeout_minutes',
        'reference_value_comparison_type', 'reference_value_duration_threshold',
        'reference_value_duration_threshold_minutes', 'timeframe_start', 'timeframe_end'
    ];

    /**
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'last_start_at', 'last_finished_at'];

    /**
     * @var array
     */
    protected $dispatchesEvents = [
        'updated' => ActionSequenceTriggerUpdated::class,
        'deleting' => ActionSequenceTriggerDeleted::class
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function properties()
    {
        return $this->hasMany('App\Property', 'belongsTo_id')->where('belongsTo_type', 'ActionSequenceTrigger');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sequence()
    {
        return $this->belongsTo('App\ActionSequence', 'action_sequence_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function logical_sensor()
    {
        return $this->belongsTo('App\LogicalSensor');
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
        $this->last_finished_at = Carbon::now();
        $this->save();

        RunningAction::where('action_sequence_trigger_id', $this->id)->delete();

        if ($this->sequence->runonce === true) {
            $this->sequence->delete();
        }

        $this->info('finish',null, null, $this->sequence);
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

        foreach (ActionSequenceTrigger::get() as $ast) {

            if (!$ast->shouldBeHandledBy($controlunit)) {
                continue;
            }

            if (!$ast->checkConsistency()) {
                continue;
            }

            if (!$ast->running() && !$ast->shouldBeRunning()) {
                continue;
            }

            if ($ast->shouldBeStarted()) {
                $ast->start();
            }

            $all_actions_finished = true;

            foreach ($ast->sequence->actions as $a) {
                $running_action = RunningAction::where('action_id', $a->id)
                    ->where('action_sequence_trigger_id', $ast->id)
                    ->first();

                /*
                 * Check whether the RunningAction
                 * ran long enough
                 */
                if (is_null($running_action)) {
                    $all_actions_finished = false;

                    if ($a->startConditionsMetForTrigger($ast, $controlunit)) {
                        RunningAction::create([
                            'action_id' => $a->id,
                            'action_sequence_trigger_id' => $ast->id,
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
                $running_actions = RunningAction::where('action_sequence_trigger_id', $ast->id)->get();
                foreach ($running_actions as $ra) {
                    $ra->delete();
                }
                $ast->finish();
            }
        }

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
     * @return bool
     */
    public function running()
    {
        return ($this->last_start_at > $this->last_finished_at
            || !is_null($this->last_start_at) && is_null($this->last_finished_at));
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
     * @return bool
     */
    public function shouldBeStarted()
    {
        return $this->shouldBeRunning() && !$this->running();
    }

    /**
     * Gets sensorreadings withing reference_value_duration_threshold_minutes
     * from the LogicalSensor and tries to match them to the trigger condition.
     * If one sensorreadings is not withing trigger bounds, return false
     * Otherwise return true
     *
     * @return bool
     */
    public function shouldBeRunning()
    {
        if (is_null($this->logical_sensor)) {

            return false;
        }

        if ($this->timeframe_start_today()->gt(Carbon::now())
            || $this->timeframe_end_today()->lt(Carbon::now())) {

            return false;
        }

        if (!is_null($this->last_finished_at) && !is_null($this->minimum_timeout_minutes) &&
            $this->last_finished_at->addMinutes($this->minimum_timeout_minutes) > Carbon::now()) {

            return false;
        }

        if (!is_null($this->next_start_not_before) && $this->next_start_not_before->lt(Carbon::now())) {
            return false;
        }


        $sensor_data = $this->logical_sensor->sensorreadings()
            ->where(
                'created_at',
                '>',
                Carbon::now()->subMinutes($this->reference_value_duration_threshold_minutes)->toDateTimeString())
            ->get();

        if ($sensor_data->count() < 1) {
            return false;
        }

        foreach ($sensor_data as $s) {
            if (!$this->matchCondition($s->adjusted_value)) {
                return false;
            }
        }


        return true;

    }

    /**
     * Returns true if $component_value matches
     * the reference value using the comparison type
     *
     * @param $component_value
     * @return bool
     */
    private function matchCondition($component_value)
    {
        if (Carbon::now()->lt($this->timeframe_start_today())
         || Carbon::now()->gt($this->timeframe_end_today())) {
            return false;
        }

        switch ($this->reference_value_comparison_type) {
            case 'equal':
                return ($component_value == $this->reference_value);
                break;
            case 'lesser':
                return ($component_value < $this->reference_value);
                break;
            case 'greater':
                return ($component_value > $this->reference_value);
                break;
        }
    }

    /**
     * @return Carbon
     */
    private function timeframe_start_today()
    {
        $time = Carbon::now();
        $time->hour = explode(':', $this->timeframe_start)[0];
        $time->minute = explode(':', $this->timeframe_start)[1];
        $time->second = 0;

        return $time;
    }

    /**
     * @return Carbon
     */
    private function timeframe_end_today()
    {
        $time = Carbon::now();
        $time->hour = explode(':', $this->timeframe_end)[0];
        $time->minute = explode(':', $this->timeframe_end)[1];
        $time->second = 0;

        return $time;
    }

    /**
     * @return string
     */
    public function icon()
    {
        return 'vanish';
    }

    /**
     *
     */
    public function url()
    {
        return url('action_sequence_triggers/' . $this->id);
    }
}
