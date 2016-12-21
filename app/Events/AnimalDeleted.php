<?php

namespace App\Events;

use App\Animal;
use App\Http\Transformers\AnimalTransformer;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AnimalDeleted implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $animal_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($animal_id)
    {
        $this->animal_id = $animal_id;
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
