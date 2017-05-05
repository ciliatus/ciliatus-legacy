<?php

namespace App;

use App\Events\ActionSequenceTriggerDeleted;
use App\Events\ActionSequenceTriggerUpdated;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ActionSequenceTrigger
 *
 * @package App
 * @property string $id
 * @property string $name
 * @property string $action_sequence_id
 * @property string $logical_sensor_id
 * @property float $reference_value
 * @property string $reference_value_comparison_type
 * @property int $reference_value_duration_threshold_minutes
 * @property int $minimum_timeout_minutes
 * @property string $timeframe_start
 * @property string $timeframe_end
 * @property \Carbon\Carbon $last_start_at
 * @property \Carbon\Carbon $last_finished_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\LogicalSensor $logical_sensor
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \App\ActionSequence $sequence
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceTrigger whereActionSequenceId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceTrigger whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceTrigger whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceTrigger whereLastFinishedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceTrigger whereLastStartAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceTrigger whereLogicalSensorId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceTrigger whereMinimumTimeoutMinutes($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceTrigger whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceTrigger whereReferenceValue($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceTrigger whereReferenceValueComparisonType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceTrigger whereReferenceValueDurationThresholdMinutes($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceTrigger whereTimeframeEnd($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceTrigger whereTimeframeStart($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceTrigger whereUpdatedAt($value)
 * @mixin \Eloquent
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

        if ($this->sequence->runonce === true) {
            $this->sequence->delete();
        }

        Log::create([
            'target_type'   =>  explode('\\', get_class($this))[count(explode('\\', get_class($this)))-1],
            'target_id'     =>  $this->id,
            'associatedWith_type' => 'ActionSequence',
            'associatedWith_id' => $this->action_sequence_id,
            'action'        => 'finish'
        ]);
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
            if (!$this->matchCondition($s->rawvalue)) {
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
