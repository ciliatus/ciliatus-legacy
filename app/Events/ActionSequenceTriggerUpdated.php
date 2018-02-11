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
     * @var array
     */
    public $action_sequence_trigger;

    /**
     * Create a new event instance.
     *
     * @param ActionSequenceTrigger $ass
     */
    public function __construct(ActionSequenceTrigger $ass)
    {
        $transformer = new ActionSequenceTriggerTransformer();
        $repository = new ActionSequenceTriggerRepository(
            ActionSequenceTrigger::with('sequence')
                                  ->find($ass->id)
        );

        $ass = $repository->show();
        $sequence = $ass->sequence()->get();
        if (!is_null($sequence)) {
            $ass->sequence = $sequence->first();
        }
        $this->action_sequence_trigger = $transformer->transform($ass->toArray());
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
