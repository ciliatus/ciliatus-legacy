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
     * @var string $id
     */
    public $id;

    /**
     * Create a new event instance.
     *
     * @param ActionSequenceSchedule $ass
     */
    public function __construct(ActionSequenceSchedule $ass)
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
