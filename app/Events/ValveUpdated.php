<?php

namespace App\Events;

use App\Http\Transformers\ValveTransformer;
use App\Repositories\GenericRepository;
use App\Valve;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

/**
 * Class ValveUpdated
 * @package App\Events
 */
class ValveUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    /**
     * @var string $id
     */
    public $id;

    /**
     * Create a new event instance.
     *
     * @param Valve $v
     */
    public function __construct(Valve $v)
    {
        $this->id = $v->id;
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
