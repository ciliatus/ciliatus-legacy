<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ActionSequence
 * @package App
 */
class ActionSequence extends CiliatusModel
{

    use Traits\Uuids;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     *
     */
    public function delete()
    {
        foreach ($this->actions as $a) {
            $a->delete();
        }
        foreach ($this->schedules as $ass) {
            $ass->delete();
        }

        foreach ($this->triggers as $ast) {
            $ast->delete();
        }

        parent::delete();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function actions()
    {
        return $this->hasMany('App\Action')->orderBy('sequence_sort_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function schedules()
    {
        return $this->hasMany('App\ActionSequenceSchedule')->orderBy('starts_at');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function triggers()
    {
        return $this->hasMany('App\ActionSequenceTrigger')->orderBy('timeframe_start');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function terrarium()
    {
        return $this->belongsTo('App\Terrarium');
    }

    /**
     * @return string
     */
    public function icon()
    {
        return 'playlist_play';
    }

    /**
     *
     */
    public function url()
    {
        return url('action_sequences/' . $this->id);
    }
}
