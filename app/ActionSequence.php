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
    public function terrarium()
    {
        return $this->belongsTo('App\Terrarium');
    }

    /**
     * @return string
     */
    public function icon()
    {
        // TODO: Implement icon() method.
    }

    /**
     *
     */
    public function url()
    {
        // TODO: Implement url() method.
    }
}
