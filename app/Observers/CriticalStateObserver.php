<?php

namespace App\Observers;


use App\CriticalState;
use App\Log;

class CriticalStateObserver
{

    /**
     * @param CriticalState $criticalState
     * @return void
     */
    public function created(CriticalState $criticalState)
    {
        Log::create([
            'target_type'   =>  'CriticalState',
            'target_id'     =>  $criticalState->id,
            'associatedWith_type' => $criticalState->belongsTo_type,
            'associatedWith_id' => $criticalState->belongsTo_id,
            'action'        => 'create'
        ]);
    }

    /**
     * @param CriticalState $criticalState
     * @return void
     */
    public function deleting(CriticalState $criticalState)
    {
        Log::create([
            'target_type'   =>  'CriticalState',
            'target_id'     =>  $criticalState->id,
            'associatedWith_type' => $criticalState->belongsTo_type,
            'associatedWith_id' => $criticalState->belongsTo_id,
            'action'        => 'delete'
        ]);
    }
}