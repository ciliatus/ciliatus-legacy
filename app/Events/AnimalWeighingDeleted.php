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
    public $animal_weighing_id;


    /**
     * Create a new event instance.
     *
     * AnimalWeighingDeleted constructor.
     * @param String $animal_weighing_id
     */
    public function __construct($animal_weighing_id)
    {
        $this->animal_weighing_id = $animal_weighing_id;
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
