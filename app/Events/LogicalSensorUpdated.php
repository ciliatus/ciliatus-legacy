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

class LogicalSensorUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $logical_sensor;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(LogicalSensor $ls)
    {
        $transformer = new LogicalSensorTransformer();
        $logical_sensor = LogicalSensor::with('thresholds')
                                       ->with('physical_sensor')
                                       ->find($ls->id);

        $logical_sensor->current_threshold_id = is_null($logical_sensor->current_threshold()) ? null : $logical_sensor->current_threshold()->id;

        $this->logical_sensor = $transformer->transform($logical_sensor->toArray());
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
