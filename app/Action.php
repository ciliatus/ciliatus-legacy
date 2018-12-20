<?php

namespace App;

use App\Traits\Uuids;
use Carbon\Carbon;

/**
 * Class Action
 * @package App
 */
class Action extends CiliatusModel
{

    use Uuids;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'last_start_at', 'last_end_at'];

    /**
     * @var array
     */
    protected $fillable = [
        'action_sequence_id', 'target_type', 'target_id', 'desired_state', 'duration_minutes', 'sequence_sort_id'
    ];

    /**
     * @return bool|null
     * @throws \Exception
     */
    public function delete()
    {
        $this->running_actions()->delete();

        return parent::delete();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function properties()
    {
        return $this->hasMany('App\Property', 'belongsTo_id')->where('belongsTo_type', 'Action');
    }

    /**
     * @return null
     */
    public function target_object()
    {
        if (!is_null($this->target_type) && !is_null($this->target_id)) {
            return ('App\\' . $this->target_type)::find($this->target_id);
        }

        return null;
    }

    /**
     * @return null
     */
    public function wait_for_started_action_object()
    {
        if (!is_null($this->wait_for_started_action_id)) {
            return Action::find($this->wait_for_started_action_id);
        }

        return null;
    }

    /**
     * @return null
     */
    public function wait_for_finished_action_object()
    {
        if (!is_null($this->wait_for_finished_action_id)) {
            return Action::find($this->wait_for_finished_action_id);
        }

        return null;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sequence()
    {
        return $this->belongsTo('App\ActionSequence', 'action_sequence_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function running_actions()
    {
        return $this->hasMany('App\RunningAction');
    }

    /**
     * @return mixed
     */
    public function target()
    {
        return ('App\\' . $this->target_type)::find($this->target_id);
    }

    /**
     * Returns true if the target component's
     * controlunit matches $controlunit parameter
     *
     * @param Controlunit $controlunit
     * @return bool
     */
    public function belongsToControlunit(Controlunit $controlunit)
    {
        if (!is_null($this->target_object())) {
            return ($this->target_object()->controlunit_id == $controlunit->id);
        }

        return false;
    }

    /**
     * Returns true of conditions
     * to start his action are met
     * for the specified action sequence schedule
     *
     * @param ActionSequenceSchedule $ass
     * @param Controlunit $controlunit null
     * @return boolean
     */
    public function startConditionsMetForSchedule(ActionSequenceSchedule $ass, Controlunit $controlunit = null)
    {
        if (!$this->target_object()->active()) {
            return false;
        }

        if (!is_null($controlunit) && !$this->belongsToControlunit($controlunit)) {
            return false;
        }

        if (!is_null($this->wait_for_started_action_id)) {
            $running_action = RunningAction::where('action_id', $this->wait_for_started_action_id)
                                           ->where('action_sequence_schedule_id', $ass->id)
                                           ->first();

            if (is_null($running_action)) {
                return false;
            }
        }

        if (!is_null($this->wait_for_finished_action_id)) {
            $running_action = RunningAction::where('action_id', $this->wait_for_finished_action_id)
                                           ->where('action_sequence_schedule_id', $ass->id)
                                           ->first();

            if (is_null($running_action)) {
                return false;
            }
            elseif (is_null($running_action->finished_at)
                || !$running_action->finished_at->lt(Carbon::now())) {

                return false;
            }

        }

        return true;
    }

    /**
     * Returns true of conditions
     * to start his action are met
     * for the specified action sequence trigger
     *
     * @param ActionSequenceTrigger $ast
     * @param Controlunit $controlunit null
     * @return boolean
     */
    public function startConditionsMetForTrigger(ActionSequenceTrigger $ast, Controlunit $controlunit = null)
    {
        if (!$this->target_object()->active()) {
            return false;
        }

        if (!is_null($controlunit) && !$this->belongsToControlunit($controlunit)) {
            return false;
        }

        if (!is_null($this->wait_for_started_action_id)) {
            $running_action = RunningAction::where('action_id', $this->wait_for_started_action_id)
                                           ->where('action_sequence_trigger_id', $ast->id)
                                           ->first();

            if (is_null($running_action)) {
                return false;
            }
        }

        if (!is_null($this->wait_for_finished_action_id)) {
            $running_action = RunningAction::where('action_id', $this->wait_for_finished_action_id)
                ->where('action_sequence_trigger_id', $ast->id)
                ->first();

            if (is_null($running_action)) {
                return false;
            }
            elseif (is_null($running_action->finished_at)
                || !$running_action->finished_at->lt(Carbon::now())) {

                return false;
            }

        }

        return true;
    }

    /**
     * Returns true of conditions
     * to start his action are met
     * for the specified action sequence intention
     *
     * @param ActionSequenceIntention $asi
     * @param Controlunit $controlunit null
     * @return boolean
     */
    public function startConditionsMetForIntention(ActionSequenceIntention $asi, Controlunit $controlunit = null)
    {
        if (!$this->target_object()->active()) {
            return false;
        }

        if (!is_null($controlunit) && !$this->belongsToControlunit($controlunit)) {
            return false;
        }

        if (!is_null($this->wait_for_started_action_id)) {
            $running_action = RunningAction::where('action_id', $this->wait_for_started_action_id)
                ->where('action_sequence_intention_id', $asi->id)
                ->first();

            if (is_null($running_action)) {
                return false;
            }
        }

        if (!is_null($this->wait_for_finished_action_id)) {
            $running_action = RunningAction::where('action_id', $this->wait_for_finished_action_id)
                ->where('action_sequence_intention_id', $asi->id)
                ->first();

            if (is_null($running_action)) {
                return false;
            }
            elseif (is_null($running_action->finished_at)
                || !$running_action->finished_at->lt(Carbon::now())) {

                return false;
            }

        }

        return true;
    }

    /**
     * @return string
     */
    public function icon()
    {
        return 'flash';
    }

    /**
     *
     */
    public function url()
    {
        return url('actions/' . $this->id);
    }
}
