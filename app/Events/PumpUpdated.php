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

class PumpUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $pump;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Pump $p)
    {
        $transformer = new PumpTransformer(
            Pump::with('valves')
                ->with('controlunit')
                ->find($p->id)
        );
        $this->pump = $transformer->transform($p->toArray());
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
