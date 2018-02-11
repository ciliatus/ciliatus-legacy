<?php

namespace App\Observers;


use App\LogicalSensor;

/**
 * Class LogicalSensorObserver
 * @package App\Observers
 */
class LogicalSensorObserver
{
    /**
     * @param  LogicalSensor $logicalSensor
     * @return void
     */
    public function creating(LogicalSensor $logicalSensor)
    {

    }

    /**
     * @param  LogicalSensor $logicalSensor
     * @return void
     */
    public function deleting(LogicalSensor $logicalSensor)
    {
        $logicalSensor->thresholds()->delete();
    }
}