<?php

namespace App\Events;


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
     * @var
     */
    public $animal_feeding_schedule_id;


    /**
     * AnimalFeedingScheduleDeleted constructor.
     * @param String $animal_feeding_schedule_id
     */
    public function __construct($animal_feeding_schedule_id)
    {
        $this->animal_feeding_schedule_id = $animal_feeding_schedule_id;
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
