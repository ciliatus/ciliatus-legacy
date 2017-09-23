<?php

namespace App\Observers;

use App\System;
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
        if (System::hasInfluxDbCapability()) {
            $sensorreading->autoWriteToInfluxDb();
        }

    }

}