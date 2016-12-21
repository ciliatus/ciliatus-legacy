<?php

namespace App\Events;

use App\Http\Transformers\ValveTransformer;
use App\Valve;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ValveDeleted implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $valve_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($valve_id)
    {
        $this->valve_id = $valve_id;
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
