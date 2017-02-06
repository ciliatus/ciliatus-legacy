<?php

namespace App;

use App\Events\ActionSequenceIntentionDeleted;
use App\Events\ActionSequenceIntentionUpdated;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ActionSequenceIntention
 * @package App
 */
class ActionSequenceIntention extends CiliatusModel
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
        'name', 'action_sequence_id', 'timeframe_start', 'timeframe_end',
        'intention', 'type', 'minimum_timeout_minutes'
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
        foreach (RunningAction::where('action_sequence_intention_id', $this->id)->get() as $ra) {
            $ra->delete();
        }

        broadcast(new ActionSequenceIntentionDeleted($this->id));

        parent::delete();
    }

    /**
     * @param array $options
     * @return bool
     */
    public function save(array $options = [])
    {
        $return = parent::save($options);

        broadcast(new ActionSequenceIntentionUpdated($this));

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

        foreach (ActionSequenceIntention::get() as $asi) {

            if (!$asi->running() && $asi->intention_active()) {
                $asi->start();
            }

            /*
             * Loop actions of the task sequence
             * and check if conditions to
             * start these actions are met
             */
            if (is_null($asi->sequence))
                continue;

            if (is_null($asi->sequence->actions))
                continue;

            $all_actions_finished = true;

            foreach ($asi->sequence->actions as $a) {
                $running_action = RunningAction::where('action_id', $a->id)
                    ->where('action_sequence_intention_id', $asi->id)
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
                    if (!$asi->intention_active()) {
                        $start = false;
                    }
                    if (!is_null($a->wait_for_started_action_id)) {
                        $running_action = RunningAction::where('action_id', $a->wait_for_started_action_id)
                            ->where('action_sequence_intention_id', $asi->id)
                            ->first();

                        if (is_null($running_action))
                            $start = false;
                    }

                    if (!is_null($a->wait_for_finished_action_id)) {
                        $running_action = RunningAction::where('action_id', $a->wait_for_finished_action_id)
                            ->where('action_sequence_intention_id', $asi->id)
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
                            'action_sequence_intention_id' => $asi->id,
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
                $running_actions = RunningAction::where('action_sequence_intention_id', $asi->id)->get();
                foreach ($running_actions as $ra) {
                    $ra->delete();
                }
                $asi->finish();
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
     * from the LogicalSensor and tries to match them to the intention condition.
     * If one sensorreadings is not withing intention bounds, return false
     * Otherwise return true
     *
     * @return bool
     */
    public function intention_active()
    {
        if ($this->timeframe_start_today()->gt(Carbon::now())
            || $this->timeframe_end_today()->lt(Carbon::now())) {

            return false;
        }

        if (!is_null($this->last_finished_at) && !is_null($this->minimum_timeout_minutes) &&
            $this->last_finished_at->addMinutes($this->minimum_timeout_minutes) > Carbon::now()) {

            return false;
        }

        $critical_states = CriticalState::whereNull('recovered_at')
                                        ->where('is_soft_state', false)
                                        ->where('belongsTo_type', 'LogicalSensor')
                                        ->get();

        if ($critical_states->count() < 1) {
            return false;
        }

        foreach ($critical_states as $cs) {
            if ($this->match_condition($cs)) {
                return true;
            }
        }

        return false;

    }

    /**
     * Returns true if critical state can be solved
     * by this intention
     *
     * @param CriticalState $cs
     * @return bool
     */
    private function match_condition(CriticalState $cs)
    {
        $ls = $cs->belongsTo_object();
        if (!is_a($ls, 'App\LogicalSensor')) {
            return false;
        }

        if ($ls->physical_sensor->belongsTo_object()->id == $this->sequence->terrarium_id) {
            if ($ls->type == $this->type) {
                switch ($this->intention) {
                    case 'increase':
                        return $ls->isCurrentValueLowerThanThreshold();
                    case 'decrease':
                        return $ls->isCurrentValueGreaterThanThreshold();
                }
            }
        }

        return false;
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
        return url('action_sequence_intentions/' . $this->id);
    }
}
