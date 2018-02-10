<?php

namespace App\Events;

use App\GenericComponent;
use App\Http\Transformers\GenericComponentTransformer;
use App\Repositories\GenericRepository;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class GenericComponentUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $generic_component;

    /**
     * Create a new event instance.
     * @param GenericComponent $generic_component
     */
    public function __construct(GenericComponent $generic_component)
    {
        $transformer = new GenericComponentTransformer();

        $this->generic_component = $transformer->transform(
            (new GenericRepository($generic_component))->show()->toArray()
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
