<?php

namespace App\Events;

use App\Terrarium;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class TerrariumUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $terrarium_id;

    /**
     * Create a new event instance.
     *
     * @param Terrarium $t
     */
    public function __construct(Terrarium $t)
    {
        $this->terrarium_id = $t->id;
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
