<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class LogicalSensor
 * @package App
 */
class LogicalSensor extends CiliatusModel
{
    use Traits\Uuids;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */

    public $incrementing = false;

    /**
     * @param array $attributes
     * @return CiliatusModel|LogicalSensor
     */
    public static function create(array $attributes = [])
    {
        $new = parent::create($attributes);
        Log::create([
            'target_type'   =>  explode('\\', get_class($new))[count(explode('\\', get_class($new)))-1],
            'target_id'     =>  $new->id,
            'associatedWith_type' => explode('\\', get_class($new))[count(explode('\\', get_class($new)))-1],
            'associatedWith_id' => $new->id,
            'action'        => 'create'
        ]);

        return $new;
    }

    /**
     *
     */
    public function delete()
    {
        Log::create([
            'target_type'   =>  explode('\\', get_class($this))[count(explode('\\', get_class($this)))-1],
            'target_id'     =>  $this->id,
            'associatedWith_type' => explode('\\', get_class($this))[count(explode('\\', get_class($this)))-1],
            'associatedWith_id' => $this->id,
            'action'        => 'delete'
        ]);

        parent::delete();
    }

    /**
     * @param array $options
     * @return bool
     */
    public function save(array $options = [])
    {
        if (!in_array('silent', $options)) {
            Log::create([
                'target_type' => explode('\\', get_class($this))[count(explode('\\', get_class($this))) - 1],
                'target_id' => $this->id,
                'associatedWith_type' => explode('\\', get_class($this))[count(explode('\\', get_class($this))) - 1],
                'associatedWith_id' => $this->id,
                'action' => 'update'
            ]);
        }

        return parent::save($options);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function physical_sensor()
    {
        return $this->belongsTo('App\PhysicalSensor');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sensorreadings()
    {
        return $this->hasMany('App\Sensorreading');
    }

    /**
     * @return float|int|mixed
     */
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

    /**
     * @param $value
     * @return bool
     */
    public function checkRawValue($value)
    {
        return ($value >= $this->rawvalue_lowerlimit && $value <= $this->rawvalue_upperlimit);
    }

    /**
     * @return mixed|string
     */
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

    /**
     * @return mixed|string
     */
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

    /**
     * @return string
     */
    public function icon()
    {
        return 'circle-o';
    }

    /**
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function url()
    {
        return url('logical_sensors/' . $this->id);
    }
}
