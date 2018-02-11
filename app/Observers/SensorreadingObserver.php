<?php

namespace App\Observers;

use App\Sensorreading;
use App\System;

/**
 * Class SensorreadingObserver
 * @package App\Observers
 */
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