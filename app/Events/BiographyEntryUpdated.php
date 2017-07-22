<?php

namespace App\Events;

use App\Http\Transformers\BiographyEntryEventTransformer;
use App\Event;
use App\Repositories\BiographyEntryEventRepository;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Log;

class BiographyEntryUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $biography_entry;

    /**
     * Create a new event instance.
     *
     * @param Event $be
     */
    public function __construct(Event $be)
    {
        $transformer = new BiographyEntryEventTransformer();

        $this->biography_entry = $transformer->transform(
            (new BiographyEntryEventRepository($be))->show()->toArray()
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
