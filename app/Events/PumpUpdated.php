<?php

namespace App\Events;

use App\Http\Transformers\PumpTransformer;
use App\Pump;
use App\Repositories\GenericRepository;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

/**
 * Class PumpUpdated
 * @package App\Events
 */
class PumpUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    /**
     * @var array
     */
    public $pump;

    /**
     * Create a new event instance.
     *
     * @param Pump $p
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
