<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Action
 * @package App
 */
class Action extends CiliatusModel
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
    protected $dates = ['created_at', 'updated_at', 'last_start_at', 'last_end_at'];

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
     * @return mixed
     */
    public function target()
    {
        return ('App\\' . $this->target_type)::find($this->target_id);
    }

    /**
     * @return string
     */
    public function icon()
    {
        return 'assignment';
    }

    /**
     *
     */
    public function url()
    {
        return url('actions/' . $this->id);
    }
}
