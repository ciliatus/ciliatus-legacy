<?php

namespace App\Events;

use App\CriticalState;
use App\Http\Transformers\CriticalStateTransformer;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Tests\Feature\CriticalStateTest;

class CriticalStateCreated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $critical_state;

    /**
     * Create a new event instance.
     *
     * @param CriticalState $critical_state
     */
    public function __construct(CriticalState $critical_state)
    {
        $critical_state = CriticalState::find($critical_state->id);
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
