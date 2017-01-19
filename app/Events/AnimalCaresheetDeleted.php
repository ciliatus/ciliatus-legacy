<?php

namespace App\Events;


use App\Property;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Log;

/**
 * Class AnimalCaresheetDeleted
 * @package App\Events
 */
class AnimalCaresheetDeleted implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    /**
     * @var array
     */
    public $animal_caresheet_id;


    /**
     * Create a new event instance.
     *
     * AnimalCaresheetDeleted constructor.
     * @param String $animal_caresheet_id
     */
    public function __construct($animal_caresheet_id)
    {
        $this->animal_caresheet_id = $animal_caresheet_id;
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
