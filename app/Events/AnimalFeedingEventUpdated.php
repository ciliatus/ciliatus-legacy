<?php

namespace App\Events;


use App\AnimalFeedingEvent;
use App\Http\Transformers\AnimalFeedingEventTransformer;
use App\Repositories\AnimalFeedingEventRepository;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

/**
 * Class AnimalFeedingEventUpdated
 * @package App\Events
 */
class AnimalFeedingEventUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    /**
     * @var array
     */
    public $animal_feeding;


    /**
     * Create a new event instance.
     *
     * AnimalFeedingUpdated constructor.
     * @param AnimalFeedingEvent $e
     */
    public function __construct(AnimalFeedingEvent $e)
    {
        $transformer = new AnimalFeedingEventTransformer();

        if (!is_null($e->belongsTo_object())) {
            foreach ($e->belongsTo_object()->feeding_schedules as $fs) {
                broadcast(new AnimalFeedingSchedulePropertyUpdated($fs));
            }
        }

        $this->animal_feeding = $transformer->transform(
            (new AnimalFeedingEventRepository($e))->show()->toArray()
        );
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('dashboard-updates');
    }
}
