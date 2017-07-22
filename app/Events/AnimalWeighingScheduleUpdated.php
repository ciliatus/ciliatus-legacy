<?php

namespace App\Events;


use App\Http\Transformers\AnimalWeighingSchedulePropertyTransformer;
use App\Http\Transformers\AnimalWeighingTransformer;
use App\Property;
use App\Repositories\AnimalWeighingScheduleRepository;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Log;

/**
 * Class AnimalWeighingScheduleUpdated
 * @package App\Events
 */
class AnimalWeighingScheduleUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    /**
     * @var array
     */
    public $animal_weighing_schedule;


    /**
     * Create a new event instance.
     *
     * AnimalWeighingScheduleUpdated constructor.
     * @param Property $p
     */
    public function __construct(Property $p)
    {
        $transformer = new AnimalWeighingSchedulePropertyTransformer();

        $this->animal_weighing_schedule = $transformer->transform(
            (new AnimalWeighingScheduleRepository($p))->show()->toArray()
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
