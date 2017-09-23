<?php

namespace App\Observers;


use App\Pump;

class PumpObserver
{
    /**
     * @param  Pump $pump
     * @return void
     */
    public function deleting(Pump $pump)
    {
        foreach ($pump->valves as $v) {
            $v->pump_id = null;
            $v->save();
        }
    }
}