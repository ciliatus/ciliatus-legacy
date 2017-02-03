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
        $new = parent::create($attributes);

        if ($new->starts_today()->lt(Carbon::now()->subMinutes(10))) {
            $new->last_start_at = Carbon::now();
            $new->last_finished_at = Carbon::now();
        }

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sequence()
    {
        return $this->belongsTo('App\ActionSequence', 'action_sequence_id', 'id');
    }

    /**
     * @return \Carbon\Carbon
     */
    public function starts_today()
    {
        $starts_today = Carbon::now();
        $starts_today->hour = explode(':', $this->starts_at)[0];
        $starts_today->minute = explode(':', $this->starts_at)[1];
        $starts_today->second = 0;

        return $starts_today;
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

        foreach (ActionSequenceSchedule::get() as $ass) {
            $starts_today = Carbon::now();
            $starts_today->hour = explode(':', $ass->starts_at)[0];
            $starts_today->minute = explode(':', $ass->starts_at)[1];
            $starts_today->second = 0;

            if ($starts_today->lt(Carbon::now()) && (is_null($ass->last_finished_at) || !$ass->last_finished_at->isToday())) {

                if (is_null($ass->last_start_at) || $ass->last_start_at->lt($starts_today)) {
                    $ass->start();
                }

                /*
                 * Loop actions of the task sequence
                 * and check if conditions to
                 * start these actions are met
                 */
                if (is_null($ass->sequence))
                    continue;

                if (is_null($ass->sequence->actions))
                    continue;

                $all_actions_finished = true;

                foreach ($ass->sequence->actions as $a) {
                    $running_action = RunningAction::where('action_id', $a->id)
                        ->where('action_sequence_schedule_id', $ass->id)
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
                        if (!is_null($a->wait_for_started_action_id)) {
                            $running_action = RunningAction::where('action_id', $a->wait_for_started_action_id)
                                ->where('action_sequence_schedule_id', $ass->id)
                                ->first();

                            if (is_null($running_action))
                                $start = false;
                        }

                        if (!is_null($a->wait_for_finished_action_id)) {
                            $running_action = RunningAction::where('action_id', $a->wait_for_finished_action_id)
                                ->where('action_sequence_schedule_id', $ass->id)
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
                                'action_sequence_schedule_id' => $ass->id,
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
                    foreach ($ass->sequence->actions as $a) {
                        $running_actions = RunningAction::where('action_sequence_schedule_id', $ass->id)->get();
                        foreach ($running_actions as $ra) {
                            $ra->delete();
                        }
                    }
                    $ass->finish();

                    if ($ass->runonce == true) {
                        $ass->delete();
                    }
                }
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
    public function will_run_today()
    {
        return (($this->starts_today()->lt(Carbon::now()) &&
                (is_null($this->last_finished_at) ||
                !$this->last_finished_at->isToday()))
                || $this->starts_today()->gt(Carbon::now()))
                && !$this->running();
    }

    /**
     * @param $minutes
     * @return bool
     */
    public function is_overdue($minutes = 10)
    {
        $starts_today = Carbon::now();
        $starts_today->hour = explode(':', $this->starts_at)[0];
        $starts_today->minute = explode(':', $this->starts_at)[1];
        $starts_today->second = 0;

        return !$this->running() && !$this->ran_today() && $starts_today->addMinutes($minutes)->lt(Carbon::now());
    }

    /**
     * @return bool
     */
    public function ran_today()
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
