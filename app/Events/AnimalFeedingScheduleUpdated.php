<?php

namespace App\Events;


use App\Http\Transformers\AnimalFeedingScheduleTransformer;
use App\Http\Transformers\AnimalFeedingTransformer;
use App\Property;
use App\Repositories\AnimalFeedingScheduleRepository;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Log;

/**
 * Class AnimalFeedingScheduleUpdated
 * @package App\Events
 */
class AnimalFeedingScheduleUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    /**
     * @var array
     */
    public $animal_feeding_schedule;


    /**
     * Create a new event instance.
     *
     * AnimalFeedingScheduleUpdated constructor.
     * @param Property $p
     */
    public function __construct(Property $p)
    {
        $transformer = new AnimalFeedingScheduleTransformer();

        $this->animal_feeding_schedule = $transformer->transform(
            (new AnimalFeedingScheduleRepository($p))->show()->toArray()
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
