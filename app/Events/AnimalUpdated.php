<?php

namespace App\Events;

use App\Http\Transformers\AnimalTransformer;
use App\Animal;
use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Log;

class AnimalUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $animal;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Animal $a)
    {
        $transformer = new AnimalTransformer(
            Animal::with('events')
                ->with('feedings')
                ->with('feeding_schedules')
                ->with('weighings')
                ->with('weighing_schedules')
                ->with('files')
                ->with('properties')
                ->with('terrarium')
                ->find($a->id)
        );
        $this->animal = $transformer->transform($a->toArray());
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
