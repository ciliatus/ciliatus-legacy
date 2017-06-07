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
 * Class ReminderDeleted
 * @package App\Events
 */
class ReminderDeleted implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    /**
     * @var array
     */
    public $reminder_id;


    /**
     * Create a new event instance.
     *
     * ReminderDeleted constructor.
     * @param String $reminder_id
     */
    public function __construct($reminder_id)
    {
        $this->reminder_id = $reminder_id;
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
