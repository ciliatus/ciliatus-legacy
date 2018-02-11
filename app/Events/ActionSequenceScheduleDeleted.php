<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

/**
 * Class ActionSequenceScheduleDeleted
 * @package App\Events
 */
class ActionSequenceScheduleDeleted implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    /**
     * @var
     */
    public $action_sequence_schedule_id;

    /**
     * Create a new event instance.
     *
     * @param $action_sequence_schedule_id
     */
    public function __construct($action_sequence_schedule_id)
    {
        $this->action_sequence_schedule_id = $action_sequence_schedule_id;
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
