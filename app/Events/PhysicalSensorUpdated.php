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

/**
 * Class PhysicalSensorUpdated
 * @package App\Events
 */
class PhysicalSensorUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    /**
     * @var string $id
     */
    public $id;

    /**
     * Create a new event instance.
     *
     * @param PhysicalSensor $ps
     */
    public function __construct(PhysicalSensor $ps)
    {
        $this->id = $ps->id;
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
