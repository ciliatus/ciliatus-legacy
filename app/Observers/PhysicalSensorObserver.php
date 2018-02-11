<?php

namespace App\Observers;


use App\PhysicalSensor;

/**
 * Class PhysicalSensorObserver
 * @package App\Observers
 */
class PhysicalSensorObserver
{
    /**
     * @param  PhysicalSensor $physicalSensor
     * @return void
     */
    public function creating(PhysicalSensor $physicalSensor)
    {

    }

    /**
     * @param  PhysicalSensor $physicalSensor
     * @return void
     */
    public function deleting(PhysicalSensor $physicalSensor)
    {
        $physicalSensor->logical_sensors()->delete();
    }
}