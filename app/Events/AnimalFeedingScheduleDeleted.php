<?php

namespace App\Events;

use App\Http\Transformers\AnimalFeedingSchedulePropertyTransformer;
use App\AnimalFeedingSchedule;
use App\Property;
use App\Repositories\AnimalFeedingScheduleRepository;
use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Log;

/**
 * Class AnimalFeedingScheduleDeleted
 * @package App\Events
 */
class AnimalFeedingScheduleDeleted implements ShouldBroadcast
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
