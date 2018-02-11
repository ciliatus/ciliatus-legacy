<?php

namespace App\Events;

use App\Event;
use App\Http\Transformers\AnimalCaresheetTransformer;
use App\Repositories\AnimalCaresheetRepository;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

/**
 * Class AnimalCaresheetUpdated
 * @package App\Events
 */
class AnimalCaresheetUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    /**
     * @var array
     */
    public $animal_caresheet;

    /**
     * Create a new event instance.
     *
     * @param Event $be
     */
    public function __construct(Event $be)
    {
        $transformer = new AnimalCaresheetTransformer();

        $this->animal_caresheet = $transformer->transform(
            (new AnimalCaresheetRepository($be))->show()->toArray()
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
