<?php

namespace App;

use App\Events\AnimalFeedingSchedulePropertyDeleted;
use App\Events\AnimalFeedingSchedulePropertyUpdated;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;

/**
 * Class AnimalFeedingScheduleProperty
 * @package App
 */
class AnimalFeedingScheduleProperty extends Property
{
    use Uuids, Notifiable;

    /**
     * @var string
     */
    protected $table = 'properties';

    /**
     * @var array
     */
    protected $dispatchesEvents = [
        'updated' => AnimalFeedingSchedulePropertyUpdated::class,
        'deleting' => AnimalFeedingSchedulePropertyDeleted::class
    ];

    /**
     *
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('type', function (Builder $builder) {
            $builder->where('type', 'AnimalFeedingSchedule');
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
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
        return url('animal_feeding_schedules/' . $this->id);
    }
}
