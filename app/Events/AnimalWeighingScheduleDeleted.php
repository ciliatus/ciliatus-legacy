<?php

namespace App\Events;

use App\Http\Transformers\AnimalWeighingScheduleTransformer;
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
    public $animal_weighing_schedule;


    /**
     * AnimalWeighingScheduleDeleted constructor.
     * @param Property $p
     */
    public function __construct(Property $p)
    {
        $transformer = new AnimalWeighingScheduleTransformer();

        $this->animal_weighing = $transformer->transform(
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
