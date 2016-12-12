<?php

namespace App\Events;

use App\Http\Transformers\ActionSequenceScheduleTransformer;
use App\ActionSequenceSchedule;
use App\Repositories\ActionSequenceScheduleRepository;
use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Log;

class ActionSequenceScheduleUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $action_sequence_schedule;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ActionSequenceSchedule $ass)
    {
        $transformer = new ActionSequenceScheduleTransformer();
        $repository = new ActionSequenceScheduleRepository(
            ActionSequenceSchedule::with('sequence')
                                  ->find($ass->id)
        );

        $ass = $repository->show();
        $sequence = $ass->sequence()->get();
        if (!is_null($sequence)) {
            $ass->sequence = $sequence->first();
        }
        $this->action_sequence_schedule = $transformer->transform($ass->toArray());
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
