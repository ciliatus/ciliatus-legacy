<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RunningAction
 * @package App
 */
class RunningAction extends CiliatusModel
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
    protected $dates = ['created_at', 'updated_at', 'started_at', 'finished_at'];

    /**
     * @var array
     */
    protected $fillable = [
        'action_id',
        'action_sequence_schedule_id',
        'action_sequence_trigger_id',
        'action_sequence_intention_id',
        'started_at'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function action()
    {
        return $this->belongsTo('App\Action');
    }

    /**
     * @return string
     */
    public function icon()
    {
        return 'assignment_turned_in';
    }

    /**
     *
     */
    public function url()
    {
        // TODO: Implement url() method.
    }
}
