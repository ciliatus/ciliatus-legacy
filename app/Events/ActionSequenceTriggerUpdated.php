<?php

namespace App\Events;

use App\ActionSequenceTrigger;
use App\Http\Transformers\ActionSequenceTriggerTransformer;
use App\Repositories\ActionSequenceTriggerRepository;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

/**
 * Class ActionSequenceTriggerUpdated
 * @package App\Events
 */
class ActionSequenceTriggerUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    /**
     * @var string $id
     */
    public $id;

    /**
     * Create a new event instance.
     *
     * @param ActionSequenceTrigger $ass
     */
    public function __construct(ActionSequenceTrigger $ass)
    {
        $this->id = $ass->id;
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
