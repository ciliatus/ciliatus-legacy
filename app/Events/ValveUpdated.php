<?php

namespace App\Events;

use App\Http\Transformers\ValveTransformer;
use App\Repositories\GenericRepository;
use App\Valve;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class ValveUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $valve;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Valve $v)
    {
        $transformer = new ValveTransformer();

        $this->valve = $transformer->transform(
            (new GenericRepository(
                Valve::with('pump', 'terrarium', 'controlunit')
                    ->find($v->id)
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
