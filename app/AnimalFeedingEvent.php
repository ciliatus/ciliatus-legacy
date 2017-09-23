<?php

namespace App;

use App\Events\AnimalFeedingEventDeleted;
use App\Events\AnimalFeedingEventUpdated;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;

/**
 * Class AnimalFeedingEvent
 * @package App
 */
class AnimalFeedingEvent extends Event
{
    use Uuids, Notifiable;

    /**
     * @var string
     */
    protected $table = 'events';

    /**
     * @var array
     */
    protected $dispatchesEvents = [
        'updated' => AnimalFeedingEventUpdated::class,
        'deleting' => AnimalFeedingEventDeleted::class
    ];

    /**
     *
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('type', function (Builder $builder) {
            $builder->where('type', 'AnimalFeeding');
        });
    }

    /**
     * @return string
     */
    public function icon()
    {
        return 'event';
    }

    /**
     * @return string
     */
    public function url()
    {
        return url('animal_feedings/' . $this->id);
    }
}
