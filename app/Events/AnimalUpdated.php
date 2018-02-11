<?php

namespace App\Events;

use App\Animal;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

/**
 * Class AnimalUpdated
 * @package App\Events
 */
class AnimalUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $animal_id;

    /**
     * Create a new event instance.
     *
     * @param Animal $a
     */
    public function __construct(Animal $a)
    {
        $this->animal_id = $a->id;
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
