<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

/**
 * Class ReadFlagSet
 * @package App\Events
 */
class ReadFlagSet implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    /**
     * @var String
     */
    public $target_type;

    /**
     * @var String
     */
    public $target_id;

    /**
     * Create a new event instance.
     *
     * @param String $target_type
     * @param String $target_id
     */
    public function __construct($target_type, $target_id)
    {
        $this->target_type = $target_type;
        $this->target_id = $target_id;
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
