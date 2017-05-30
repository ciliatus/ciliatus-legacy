<?php

namespace App\Events;

use App\CriticalState;
use App\Http\Transformers\CriticalStateTransformer;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Log;

class CriticalStateCreated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $critical_state;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(CriticalState $critical_state)
    {
        $critical_state = clone $critical_state;
        $critical_state->belongsTo_object = $critical_state->belongsTo_object();
        $critical_state->belongsTo_object->icon = $critical_state->belongsTo_object->icon();
        $critical_state->belongsTo_object->url = $critical_state->belongsTo_object->url();
        $this->critical_state = (new CriticalStateTransformer())->transform($critical_state->toArray());
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
