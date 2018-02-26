<?php

namespace App\Events;


use App\AnimalFeedingScheduleProperty;
use App\Http\Transformers\AnimalFeedingSchedulePropertyTransformer;
use App\Repositories\AnimalFeedingSchedulePropertyRepository;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

/**
 * Class AnimalFeedingSchedulePropertyUpdated
 * @package App\Events
 */
class AnimalFeedingSchedulePropertyUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    /**
     * @var string $id
     */
    public $id;


    /**
     * Create a new event instance.
     *
     * AnimalFeedingScheduleUpdated constructor.
     * @param AnimalFeedingScheduleProperty $p
     */
    public function __construct(AnimalFeedingScheduleProperty $p)
    {
        $this->id = $p->id;
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
