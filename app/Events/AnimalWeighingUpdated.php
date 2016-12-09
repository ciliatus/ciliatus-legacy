<?php

namespace App\Events;


use App\Http\Transformers\AnimalWeighingTransformer;
use App\Property;
use App\Event;
use App\Repositories\AnimalWeighingRepository;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Log;

/**
 * Class AnimalWeighingUpdated
 * @package App\Events
 */
class AnimalWeighingUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    /**
     * @var array
     */
    public $animal_weighing;


    /**
     * Create a new event instance.
     *
     * AnimalWeighingUpdated constructor.
     * @param Event $e
     */
    public function __construct(Event $e)
    {
        $transformer = new AnimalWeighingTransformer();

        foreach ($e->belongsTo_object->weighing_schedules as $fs) {
            broadcast(new AnimalWeighingScheduleUpdated($fs));
        }

        $this->animal_weighing = $transformer->transform(
            (new AnimalWeighingRepository($e))->show()->toArray()
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
