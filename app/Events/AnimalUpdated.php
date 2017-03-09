<?php

namespace App\Events;

use App\Http\Transformers\AnimalTransformer;
use App\Animal;
use App\Repositories\AnimalRepository;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Log;

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
