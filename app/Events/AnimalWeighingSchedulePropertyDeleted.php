<?php

namespace App\Events;

use App\AnimalWeighingScheduleProperty;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

/**
 * Class AnimalWeighingScheduleEventDeleted
 * @package App\Events
 */
class AnimalWeighingSchedulePropertyDeleted implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    /**
     * @var string $id
     */
    public $id;


    /**
     * AnimalWeighingScheduleDeleted constructor.
     * @param AnimalWeighingScheduleProperty $animal_weighing_schedule
     */
    public function __construct(AnimalWeighingScheduleProperty $animal_weighing_schedule)
    {
        $this->id = $animal_weighing_schedule->id;
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
