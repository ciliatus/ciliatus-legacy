<?php

namespace App\Events;

use App\BiographyEntryEvent;
use App\Http\Transformers\BiographyEntryEventTransformer;
use App\Repositories\BiographyEntryEventRepository;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class BiographyEntryEventUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $biography_entry;

    /**
     * Create a new event instance.
     *
     * @param BiographyEntryEvent $be
     */
    public function __construct(BiographyEntryEvent $be)
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
