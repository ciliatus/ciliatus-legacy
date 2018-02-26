<?php

namespace App\Events;

use App\ActionSequenceTrigger;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

/**
 * Class ActionSequenceTriggerDeleted
 * @package App\Events
 */
class ActionSequenceTriggerDeleted implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    /**
     * @var string $id
     */
    public $id;

    /**
     * Create a new event instance.
     *
     * @param ActionSequenceTrigger $action_sequence_trigger
     */
    public function __construct(ActionSequenceTrigger $action_sequence_trigger)
    {
        $this->id = $action_sequence_trigger->id;
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
