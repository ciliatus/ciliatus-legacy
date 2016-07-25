<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class LogicalSensor
 * @package App
 */
class LogicalSensor extends Model
{
    use Traits\Uuids;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */

    public $incrementing = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function physical_sensor()
    {
        return $this->belongsTo('App\PhysicalSensor');
    }

    public function sensorreadings()
    {
        return $this->hasMany('App\Sensorreading');
    }

    public function getCurrentCookedValue()
    {
        switch ($this->type) {
            case 'temperature_celsius':
                return round($this->rawvalue, 1);
            case 'humidity_percent':
                return (int)$this->rawvalue;
            default:
                return $this->rawvalue;
        }
    }

    public function checkRawValue($value)
    {
        return ($value >= $this->rawvalue_lowerlimit && $value <= $this->rawvalue_upperlimit);
    }

    public function typeReadable()
    {
        switch ($this->type) {
            case 'humidity_percent':
                return 'Humidity (%)';
            case 'temperature_celsius':
                return 'Temperature (°C)';
            default:
                return $this->type;
        }
    }

    public function valueReadable()
    {
        switch ($this->type) {
            case 'humidity_percent':
                return $this->getCurrentCookedValue() . '%';
            case 'temperature_celsius':
                return $this->getCurrentCookedValue() . '°C';
            default:
                return $this->type;
        }
    }
}
