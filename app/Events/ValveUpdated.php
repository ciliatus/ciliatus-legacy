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

class ValveUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $valve;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Valve $v)
    {
        $transformer = new ValveTransformer();
        $this->valve = $transformer->transform($v->toArray());
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
