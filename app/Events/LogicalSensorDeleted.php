<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class LogicalSensorDeleted implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $logical_sensor_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($logical_sensor_id)
    {
        $this->logical_sensor_id = $logical_sensor_id;
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
