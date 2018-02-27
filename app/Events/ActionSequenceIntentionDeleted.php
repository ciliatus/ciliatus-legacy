<?php

namespace App\Events;

use App\ActionSequenceIntention;
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
     * @var string $id
     */
    public $id;

    /**
     * Create a new event instance.
     *
     * @param ActionSequenceIntention $action_sequence_intention
     */
    public function __construct(ActionSequenceIntention $action_sequence_intention)
    {
        $this->id = $action_sequence_intention->id;
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
