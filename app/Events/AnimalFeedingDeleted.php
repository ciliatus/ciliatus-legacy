<?php

namespace App\Events;


use App\Http\Transformers\AnimalFeedingTransformer;
use App\Property;
use App\Repositories\AnimalFeedingRepository;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Log;

/**
 * Class AnimalFeedingDeleted
 * @package App\Events
 */
class AnimalFeedingDeleted implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    /**
     * @var array
     */
    public $animal_feeding;


    /**
     * Create a new event instance.
     *
     * AnimalFeedingDeleted constructor.
     * @param Property $p
     */
    public function __construct(Property $p)
    {
        $transformer = new AnimalFeedingTransformer();

        $this->animal_feeding = $transformer->transform(
            (new AnimalFeedingRepository($p))->toArray()
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
