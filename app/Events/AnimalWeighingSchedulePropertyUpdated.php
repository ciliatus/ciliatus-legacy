<?php

namespace App\Events;


use App\AnimalWeighingScheduleProperty;
use App\Http\Transformers\AnimalWeighingSchedulePropertyTransformer;
use App\Repositories\AnimalWeighingSchedulePropertyRepository;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

/**
 * Class AnimalWeighingSchedulePropertyUpdated
 * @package App\Events
 */
class AnimalWeighingSchedulePropertyUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    /**
     * @var string $id
     */
    public $id;


    /**
     * Create a new event instance.
     *
     * AnimalWeighingScheduleUpdated constructor.
     * @param AnimalWeighingScheduleProperty $s
     */
    public function __construct(AnimalWeighingScheduleProperty $s)
    {
        $this->id = $s->id;
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
