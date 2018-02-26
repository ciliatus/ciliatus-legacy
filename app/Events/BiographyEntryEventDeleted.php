<?php

namespace App\Events;


use App\BiographyEntryEvent;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

/**
 * Class BiographyEntryEventDeleted
 * @package App\Events
 */
class BiographyEntryEventDeleted implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    /**
     * @var string $id
     */
    public $id;


    /**
     * Create a new event instance.
     *
     * BiographyEntryDeleted constructor.
     * @param BiographyEntryEvent $biography_entry
     */
    public function __construct(BiographyEntryEvent $biography_entry)
    {
        $this->id = $biography_entry->id;
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
