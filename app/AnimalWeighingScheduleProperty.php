<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class AnimalFeedingWeighingProperty
 *
 */
class AnimalWeighingScheduleProperty extends Property
{
    use Traits\Uuids;

    /**
     * @var string
     */
    protected $table = 'properties';

    /**
     *
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('type', function (Builder $builder) {
            $builder->where('type', 'AnimalWeighingSchedule');
        });
    }

    public function animal()
    {
        return $this->belongsTo('App\Animal', 'belongsTo_id');
    }

    /**
     * @return string
     */
    public function icon()
    {
        return 'schedule';
    }

    /**
     * @return string
     */
    public function url()
    {
        return url('animal_weighing_schedules/' . $this->id);
    }
}
