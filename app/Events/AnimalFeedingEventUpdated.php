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
     * @var string $id
     */
    public $id;


    /**
     * Create a new event instance.
     *
     * AnimalFeedingUpdated constructor.
     * @param AnimalFeedingEvent $e
     */
    public function __construct(AnimalFeedingEvent $e)
    {
        $this->id = $e->id;
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
