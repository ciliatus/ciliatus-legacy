<?php

namespace App\Events;

use App\ActionSequenceIntention;
use App\Http\Transformers\ActionSequenceIntentionTransformer;
use App\Repositories\ActionSequenceIntentionRepository;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

/**
 * Class ActionSequenceIntentionUpdated
 * @package App\Events
 */
class ActionSequenceIntentionUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    /**
     * @var string $id
     */
    public $id;

    /**
     * Create a new event instance.
     *
     * @param ActionSequenceIntention $asi
     */
    public function __construct(ActionSequenceIntention $asi)
    {
        $this->id = $asi->id;
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
