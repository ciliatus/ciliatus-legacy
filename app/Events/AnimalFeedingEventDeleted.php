<?php

namespace App\Events;


use App\AnimalFeedingEvent;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

/**
 * Class AnimalFeedingEventDeleted
 * @package App\Events
 */
class AnimalFeedingEventDeleted implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    /**
     * @var string $id
     */
    public $id;


    /**
     * Create a new event instance.
     *
     * AnimalFeedingDeleted constructor.
     * @param AnimalFeedingEvent $animal_feeding
     */
    public function __construct(AnimalFeedingEvent $animal_feeding)
    {
        $this->id = $animal_feeding->id;
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
