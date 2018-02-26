<?php

namespace App\Events;


use App\AnimalWeighingEvent;
use App\Http\Transformers\AnimalWeighingEventTransformer;
use App\Repositories\AnimalWeighingEventRepository;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

/**
 * Class AnimalWeighingEventUpdated
 * @package App\Events
 */
class AnimalWeighingEventUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    /**
     * @var string $id
     */
    public $id;

    /**
     * Create a new event instance.
     *
     * AnimalWeighingUpdated constructor.
     * @param AnimalWeighingEvent $e
     */
    public function __construct(AnimalWeighingEvent $e)
    {
        $this->id = $e->id;
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
