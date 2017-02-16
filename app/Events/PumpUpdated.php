<?php

namespace App\Events;

use App\Http\Transformers\PumpTransformer;
use App\Pump;
use App\Repositories\GenericRepository;
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
        $transformer = new PumpTransformer();
        $this->pump = $transformer->transform(
            (new GenericRepository(
                Pump::with('valves', 'controlunit')
                    ->find($p->id)
            ))->show()
              ->toArray()
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
