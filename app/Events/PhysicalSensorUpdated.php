<?php

namespace App\Events;

use App\Http\Transformers\PhysicalSensorTransformer;
use App\PhysicalSensor;
use App\Repositories\GenericRepository;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class PhysicalSensorUpdated implements ShouldBroadcast
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
        $this->physical_sensor = $transformer->transform(
            (new GenericRepository(
                PhysicalSensor::with('logical_sensors', 'controlunit', 'terrarium')->find($ps->id)
            ))->show()
              ->toArray()
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
