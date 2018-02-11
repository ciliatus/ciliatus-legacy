<?php

namespace App\Events;

use App\ActionSequenceSchedule;
use App\Http\Transformers\ActionSequenceScheduleTransformer;
use App\Repositories\ActionSequenceScheduleRepository;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

/**
 * Class ActionSequenceScheduleUpdated
 * @package App\Events
 */
class ActionSequenceScheduleUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    /**
     * @var array
     */
    public $action_sequence_schedule;

    /**
     * Create a new event instance.
     *
     * @param ActionSequenceSchedule $ass
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
