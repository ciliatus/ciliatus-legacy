<?php

namespace App\Events;

use App\Http\Transformers\TerrariumTransformer;
use App\Repositories\TerrariumRepository;
use App\Terrarium;
use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Log;

class TerrariumDeleted implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $terrarium_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($terrarium_id)
    {
        $this->terrarium_id = $terrarium_id;
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
