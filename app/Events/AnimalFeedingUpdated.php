<?php

namespace App\Events;


use App\Http\Transformers\AnimalFeedingTransformer;
use App\Property;
use App\Event;
use App\Repositories\AnimalFeedingRepository;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Log;

/**
 * Class AnimalFeedingUpdated
 * @package App\Events
 */
class AnimalFeedingUpdated implements ShouldBroadcast
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
     * @param Event $e
     */
    public function __construct(Event $e)
    {
        $transformer = new AnimalFeedingTransformer();

        foreach ($e->belongsTo_object->feeding_schedules as $fs) {
            broadcast(new AnimalFeedingScheduleUpdated($fs));
        }

        $this->animal_feeding = $transformer->transform(
            (new AnimalFeedingRepository($e))->show()->toArray()
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
