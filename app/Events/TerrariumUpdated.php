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

class TerrariumUpdated implements ShouldBroadcast
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
        $repository = new TerrariumRepository(
            Terrarium::with('action_sequences')
                    ->with('animals')
                    ->with('files')
                    ->with('physical_sensors')
                    ->with('valves')
                    ->find($t->id)
        );
        $t = $repository->show()->toArray();
        $this->terrarium = $transformer->transform($t);
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
