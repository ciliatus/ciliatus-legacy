<?php

namespace App\Observers;

use App\Sensorreading;

class SensorreadingObserver
{
    /**
     * Listen to the Sensorreading created event.
     *
     * @param  Sensorreading  $sensorreading
     * @return void
     */
    public function created(Sensorreading $sensorreading)
    {
        $sensorreading->autoWriteToInfluxDb();
    }

    /**
     * Listen to the Sensorreading deleting event.
     *
     * @param  Sensorreading  $sensorreading
     * @return void
     */
    public function deleting(Sensorreading $sensorreading)
    {
        //
    }
}