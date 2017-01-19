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

class ControlunitUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $controlunit;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Controlunit $cu)
    {
        $transformer = new ControlunitTransformer();
        $this->controlunit = $transformer->transform(
            Controlunit::with('physical_sensors')->find($cu->id)->toArray()
        );
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
