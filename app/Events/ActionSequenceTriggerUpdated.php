<?php

namespace App\Events;

use App\Http\Transformers\ActionSequenceTriggerTransformer;
use App\ActionSequenceTrigger;
use App\Repositories\ActionSequenceTriggerRepository;
use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Log;

class ActionSequenceTriggerUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $action_sequence_trigger;

    /**
     * Create a new event instance.
     *
     * @return void
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
