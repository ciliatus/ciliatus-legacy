<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

/**
 * Class ActionSequenceIntentionDeleted
 * @package App\Events
 */
class ActionSequenceIntentionDeleted implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    /**
     * @var
     */
    public $action_sequence_intention_id;

    /**
     * Create a new event instance.
     *
     * @param $action_sequence_intention_id
     */
    public function __construct($action_sequence_intention_id)
    {
        $this->action_sequence_intention_id = $action_sequence_intention_id;
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
