<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

/**
 * Class AnimalWeighingEventDeleted
 * @package App\Events
 */
class AnimalWeighingEventDeleted implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    /**
     * @var array
     */
    public $animal_weighing_id;


    /**
     * Create a new event instance.
     *
     * AnimalWeighingDeleted constructor.
     * @param String $animal_weighing_id
     */
    public function __construct($animal_weighing_id)
    {
        $this->animal_weighing_id = $animal_weighing_id;
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
