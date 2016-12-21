<?php

namespace App\Events;

use App\Controlunit;
use App\Http\Transformers\ControlunitTransformer;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ControlunitDeleted implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $controlunit_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($controlunit_id)
    {
        $this->controlunit_id = $controlunit_id;
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
