<?php

namespace App;

use App\Events\ActionSequenceTriggerDeleted;
use App\Events\ActionSequenceTriggerUpdated;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ActionSequenceTrigger
 * @package App
 */
class ActionSequenceTrigger extends CiliatusModel
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
        'name', 'action_sequence_id', 'logical_sensor_id', 'reference_value', 'minimum_timeout_minutes',
        'reference_value_comparison_type', 'reference_value_duration_threshold',
        'reference_value_duration_threshold_minutes', 'timeframe_start', 'timeframe_end'
    ];

    /**
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'last_start_at', 'last_finished_at'];

    /**
     *
     */
    public function delete()
    {
        foreach (RunningAction::where('action_sequence_trigger_id', $this->id)->get() as $ra) {
            $ra->delete();
        }

        broadcast(new ActionSequenceTriggerDeleted($this->id));

        parent::delete();
    }

    /**
     * @param array $options
     * @return bool
     */
    public function save(array $options = [])
    {
        $return = parent::save($options);

        broadcast(new ActionSequenceTriggerUpdated($this));

        return $return;
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
        $this->last_finished_at = Carbon::now();
        $this->save();

        Log::create([
            'target_type'   =>  explode('\\', get_class($this))[count(explode('\\', get_class($this)))-1],
            'target_id'     =>  $this->id,
            'associatedWith_type' => 'ActionSequence',
            'associatedWith_id' => $this->action_sequence_id,
            'action'        => 'finish'
        ]);
    }

    /**
     * @return array|void
     */
    public static function createAndUpdateRunningActions()
    {
        if (ActionSequence::stopped()) {
            return;
        }

        foreach (ActionSequenceTrigger::get() as $ast) {

            if (!$ast->running() && $ast->trigger_active()) {
                $ast->start();
            }

            /*
             * Loop actions of the task sequence
             * and check if conditions to
             * start these actions are met
             */
            if (is_null($ast->sequence))
                continue;

            if (is_null($ast->sequence->actions))
                continue;

            $all_actions_finished = true;

            foreach ($ast->sequence->actions as $a) {
                $running_action = RunningAction::where('action_id', $a->id)
                    ->where('action_sequence_trigger_id', $ast->id)
                    ->first();

                /*
                 * Check whether the RunningAction
                 * ran long enough
                 */
                if (!is_null($running_action)
                    && $running_action->started_at->addMinutes($a->duration_minutes)->lt(Carbon::now())
                    && is_null($running_action->finished_at)) {

                    $running_action->finished_at = Carbon::now();
                    $running_action->save();
                }
                elseif (is_null($running_action)) {
                    $all_actions_finished = false;
                    $start = true;

                    /*
                     * Check conditions before
                     * starting the action
                     */
                    if (!$ast->trigger_active()) {
                        $start = false;
                    }
                    if (!is_null($a->wait_for_started_action_id)) {
                        $running_action = RunningAction::where('action_id', $a->wait_for_started_action_id)
                            ->where('action_sequence_trigger_id', $ast->id)
                            ->first();

                        if (is_null($running_action))
                            $start = false;
                    }

                    if (!is_null($a->wait_for_finished_action_id)) {
                        $running_action = RunningAction::where('action_id', $a->wait_for_finished_action_id)
                            ->where('action_sequence_trigger_id', $ast->id)
                            ->first();

                        if (is_null($running_action))
                            $start = false;
                        elseif (is_null($running_action->finished_at)
                            || !$running_action->finished_at->lt(Carbon::now())) {

                            $start = false;
                        }

                    }

                    /*
                     * Create a new RunningAction
                     */
                    if ($start) {
                        $new_ra = RunningAction::create([
                            'action_id' => $a->id,
                            'action_sequence_trigger_id' => $ast->id,
                            'started_at' => Carbon::now()
                        ]);
                    }
                }
                elseif (!is_null($running_action)
                    && $running_action->started_at->addMinutes($a->duration_minutes)->lt(Carbon::now())) {
                }
                else {
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
     * @return bool
     */
    public function running()
    {
        return ($this->last_start_at > $this->last_finished_at
            || !is_null($this->last_start_at) && is_null($this->last_finished_at));
    }

    /**
     * Gets sensorreadings withing reference_value_duration_threshold_minutes
     * from the LogicalSensor and tries to match them to the trigger condition.
     * If one sensorreadings is not withing trigger bounds, return false
     * Otherwise return true
     *
     * @return bool
     */
    public function trigger_active()
    {
        $logical_sensor = LogicalSensor::find($this->logical_sensor_id);
        if (is_null($logical_sensor)) {
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

        $sensor_data = $logical_sensor->sensorreadings()
            ->where('created_at', '>', Carbon::now()->subMinutes($this->reference_value_duration_threshold_minutes)->toDateTimeString())
            ->get();

        if ($sensor_data->count() < 1) {
            return false;
        }

        foreach ($sensor_data as $s) {
            if (!$this->match_condition($s->rawvalue)) {
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
    private function match_condition($component_value)
    {
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
     * @return static
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
     * @return static
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
        return 'flare';
    }

    /**
     *
     */
    public function url()
    {
        return url('action_sequence_triggers/' . $this->id);
    }
}
