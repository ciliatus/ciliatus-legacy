<?php

namespace App\Events;


use App\Property;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Log;

/**
 * Class BiographyEntryDeleted
 * @package App\Events
 */
class BiographyEntryDeleted implements ShouldBroadcast
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
