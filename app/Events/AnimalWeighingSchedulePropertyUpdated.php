<?php

namespace App\Events;


use App\AnimalWeighingScheduleProperty;
use App\Http\Transformers\AnimalWeighingSchedulePropertyTransformer;
use App\Repositories\AnimalWeighingSchedulePropertyRepository;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * Class AnimalWeighingSchedulePropertyUpdated
 * @package App\Events
 */
class AnimalWeighingSchedulePropertyUpdated implements ShouldBroadcast
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
     * @param AnimalWeighingScheduleProperty $p
     */
    public function __construct(AnimalWeighingScheduleProperty $p)
    {
        $transformer = new AnimalWeighingSchedulePropertyTransformer();

        $this->animal_weighing_schedule = $transformer->transform(
            (new AnimalWeighingSchedulePropertyRepository($p))->show()->toArray()
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
