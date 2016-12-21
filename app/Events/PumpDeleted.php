<?php

namespace App\Events;

use App\Http\Transformers\PumpTransformer;
use App\Pump;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PumpDeleted implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $pump_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($pump_id)
    {
        $this->pump_id = $pump_id;
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
