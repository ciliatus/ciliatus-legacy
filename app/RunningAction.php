<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RunningAction
 *
 * @package App
 * @property string $id
 * @property string $action_id
 * @property string $action_sequence_schedule_id
 * @property \Carbon\Carbon $started_at
 * @property \Carbon\Carbon $finished_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $action_sequence_trigger_id
 * @property string $action_sequence_intention_id
 * @property-read \App\Action $action
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @method static \Illuminate\Database\Query\Builder|\App\RunningAction whereActionId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\RunningAction whereActionSequenceIntentionId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\RunningAction whereActionSequenceScheduleId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\RunningAction whereActionSequenceTriggerId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\RunningAction whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\RunningAction whereFinishedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\RunningAction whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\RunningAction whereStartedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\RunningAction whereUpdatedAt($value)
 * @mixin \Eloquent
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
