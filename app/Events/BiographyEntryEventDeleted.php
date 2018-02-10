<?php

namespace App\Events;


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
     * @var array
     */
    public $biography_entry_id;


    /**
     * Create a new event instance.
     *
     * BiographyEntryDeleted constructor.
     * @param String $biography_entry_id
     */
    public function __construct($biography_entry_id)
    {
        $this->biography_entry_id = $biography_entry_id;
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
