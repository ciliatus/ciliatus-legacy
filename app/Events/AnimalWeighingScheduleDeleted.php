<?php

namespace App\Events;

use App\Http\Transformers\AnimalWeighingSchedulePropertyTransformer;
use App\AnimalWeighingSchedule;
use App\Property;
use App\Repositories\AnimalWeighingScheduleRepository;
use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Log;

/**
 * Class AnimalWeighingScheduleDeleted
 * @package App\Events
 */
class AnimalWeighingScheduleDeleted implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    /**
     * @var
     */
    public $animal_weighing_schedule_id;


    /**
     * AnimalWeighingScheduleDeleted constructor.
     * @param String $animal_weighing_schedule_id
     */
    public function __construct($animal_weighing_schedule_id)
    {
        $this->animal_weighing_schedule_id = $animal_weighing_schedule_id;
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
