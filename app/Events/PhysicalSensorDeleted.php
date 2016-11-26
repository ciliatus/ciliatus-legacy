<?php

namespace App\Events;

use App\Http\Transformers\PhysicalSensorTransformer;
use App\PhysicalSensor;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PhysicalSensorDeleted implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $physical_sensor;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(PhysicalSensor $ps)
    {
        $transformer = new PhysicalSensorTransformer();
        $this->physical_sensor = $transformer->transform($ps->toArray());
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
