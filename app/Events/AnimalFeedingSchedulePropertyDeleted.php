<?php

namespace App\Events;


use App\AnimalFeedingScheduleProperty;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

/**
 * Class AnimalFeedingSchedulePropertyDeleted
 * @package App\Events
 */
class AnimalFeedingSchedulePropertyDeleted implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    /**
     * @var string $id
     */
    public $id;


    /**
     * AnimalFeedingScheduleDeleted constructor.
     * @param AnimalFeedingScheduleProperty $animal_feeding_schedule
     */
    public function __construct(AnimalFeedingScheduleProperty $animal_feeding_schedule)
    {
        $this->id = $animal_feeding_schedule->id;
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
