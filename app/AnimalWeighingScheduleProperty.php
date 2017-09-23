<?php

namespace App;

use App\Events\AnimalWeighingSchedulePropertyDeleted;
use App\Events\AnimalWeighingSchedulePropertyUpdated;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;

/**
 * Class AnimalWeighingScheduleProperty
 * @package App
 */
class AnimalWeighingScheduleProperty extends Property
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
        'updated' => AnimalWeighingSchedulePropertyUpdated::class,
        'deleting' => AnimalWeighingSchedulePropertyDeleted::class
    ];

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
        return url('animal_weighing_schedules/' . $this->id);
    }
}
