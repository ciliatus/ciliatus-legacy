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

    public $terrarium;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Terrarium $t)
    {
        $transformer = new TerrariumTransformer();
        $repository = new TerrariumRepository($t);
        $this->terrarium = $transformer->transform($repository->show()->toArray());
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
