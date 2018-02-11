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
     * @var array
     */
    public $action_sequence_intention;

    /**
     * Create a new event instance.
     *
     * @param ActionSequenceIntention $asi
     */
    public function __construct(ActionSequenceIntention $asi)
    {
        $transformer = new ActionSequenceIntentionTransformer();
        $repository = new ActionSequenceIntentionRepository(
            ActionSequenceIntention::with('sequence')
                                  ->find($asi->id)
        );

        $asi = $repository->show();
        $sequence = $asi->sequence()->get();
        if (!is_null($sequence)) {
            $asi->sequence = $sequence->first();
        }
        $this->action_sequence_intention = $transformer->transform($asi->toArray());
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
