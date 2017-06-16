<?php

namespace App\Events;

use App\Http\Transformers\ReminderTransformer;
use App\Event;
use App\Repositories\ReminderRepository;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Log;

class ReminderUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $reminder;

    /**
     * Create a new event instance.
     *
     * @param Event $be
     */
    public function __construct(Event $be)
    {
        $transformer = new ReminderTransformer();

        $this->reminder = $transformer->transform(
            (new ReminderRepository($be))->show()->toArray()
        );
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
