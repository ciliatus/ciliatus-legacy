<?php

namespace App\Events;


use App\Http\Transformers\AnimalWeighingTransformer;
use App\Property;
use App\Repositories\AnimalWeighingRepository;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Log;

/**
 * Class AnimalWeighingDeleted
 * @package App\Events
 */
class AnimalWeighingDeleted implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    /**
     * @var array
     */
    public $animal_weighing;


    /**
     * Create a new event instance.
     *
     * AnimalWeighingDeleted constructor.
     * @param Property $p
     */
    public function __construct(Property $p)
    {
        $transformer = new AnimalWeighingTransformer();

        $this->animal_weighing = $transformer->transform(
            (new AnimalWeighingRepository($p))->toArray()
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
