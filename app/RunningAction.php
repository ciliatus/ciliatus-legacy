<?php

namespace App;

use App\Traits\Uuids;
use Carbon\Carbon;

/**
 * Class RunningAction
 * @package App
 */
class RunningAction extends CiliatusModel
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function properties()
    {
        return $this->hasMany('App\Property', 'belongsTo_id')->where('belongsTo_type', 'RunningAction');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function action()
    {
        return $this->belongsTo('App\Action');
    }

    /**
     *
     */
    public function stop()
    {
        $this->finished_at = Carbon::now();
        $this->save();
    }

    /**
     * Return true if the actions running duration is up
     * and the RunningAction has not finished yet
     *
     * @return bool
     */
    public function shouldBeStopped()
    {
        return $this->started_at->addMinutes($this->action->duration_minutes)->lt(Carbon::now())
            && is_null($this->finished_at);
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
