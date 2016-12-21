<?php

namespace App\Events;

use App\Http\Transformers\LogicalSensorTransformer;
use App\LogicalSensor;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

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
