<?php

namespace App\Events;

use App\CriticalState;
use App\Http\Transformers\CriticalStateTransformer;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Log;

class CriticalStateDeleted implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $critical_state;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(CriticalState $critical_state)
    {
        $this->critical_state = $critical_state;
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
