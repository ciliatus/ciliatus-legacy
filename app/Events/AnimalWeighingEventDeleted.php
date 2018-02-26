<?php

namespace App\Events;

use App\AnimalWeighingEvent;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

/**
 * Class AnimalWeighingEventDeleted
 * @package App\Events
 */
class AnimalWeighingEventDeleted implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    /**
     * @var string $id
     */
    public $id;


    /**
     * Create a new event instance.
     *
     * AnimalWeighingDeleted constructor.
     * @param AnimalWeighingEvent $animal_weighing
     */
    public function __construct(AnimalWeighingEvent $animal_weighing)
    {
        $this->id = $animal_weighing->id;
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
