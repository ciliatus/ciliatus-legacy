<?php

namespace App\Events;


use App\AnimalWeighingEvent;
use App\Http\Transformers\AnimalWeighingEventTransformer;
use App\Repositories\AnimalWeighingEventRepository;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

/**
 * Class AnimalWeighingEventUpdated
 * @package App\Events
 */
class AnimalWeighingEventUpdated implements ShouldBroadcast
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
     * @param AnimalWeighingEvent $e
     */
    public function __construct(AnimalWeighingEvent $e)
    {
        $transformer = new AnimalWeighingEventTransformer();

        if (!is_null($e->belongsTo_object())) {
            foreach ($e->belongsTo_object()->weighing_schedules as $fs) {
                broadcast(new AnimalWeighingSchedulePropertyUpdated($fs));
            }
        }

        $this->animal_weighing = $transformer->transform(
            (new AnimalWeighingEventRepository($e))->show()->toArray()
        );
        $this->e = $e;
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
