<?php

namespace App\Events;

use App\Http\Transformers\ActionSequenceScheduleTransformer;
use App\ActionSequenceSchedule;
use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Log;

class ActionSequenceScheduleDeleted implements ShouldBroadcast
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
