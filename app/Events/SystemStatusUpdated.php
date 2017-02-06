<?php

namespace App\Events;

use App\System;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SystemStatusUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $system_status;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->system_status = System::status();
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
