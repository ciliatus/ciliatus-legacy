<?php

namespace App;

use App\Traits\Uuids;
use App\Traits\WritesToInfluxDb;

/**
 * Class Sensorreading
 * @package App
 */
class Sensorreading extends CiliatusModel
{
    use Uuids, WritesToInfluxDb;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = [
        'sensorreadinggroup_id', 'logical_sensor_id', 'rawvalue', 'rawvalue_adjustment', 'adjusted_value', 'read_at'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'is_anomaly' => 'boolean'
    ];

    /**
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at', 'read_at'
    ];

    /**
     * Automatically writes relevant data to InfluxDb
     */
    public function autoWriteToInfluxDb()
    {
        if (is_null($this->logical_sensor)) {
            return false;
        }

        if (is_null($this->logical_sensor) ||
            is_null($this->logical_sensor->physical_sensor) ||
            is_null($this->logical_sensor->physical_sensor->terrarium)) {

            \Log::warning('Check configuration of Logical Sensor ' . $this->id . '. Link to Terrarium is broken');
            return false;

        }

        $result = $this->writeToInfluxDb(
            'logical_sensor_readings',
            $this->adjusted_value,
            [
                'logical_sensor_type'   => $this->logical_sensor->type,
                'logical_sensor'        => $this->logical_sensor->name,
                'physical_sensor'       => $this->logical_sensor->physical_sensor->name,
                'terrarium'             => $this->logical_sensor->physical_sensor->terrarium->name
            ]
        );

        $threshold = $this->logical_sensor->current_threshold();
        if (is_null($threshold)) {
            return $result;
        }

        if (!is_null($threshold->adjusted_value_lowerlimit)) {
            $result = $result && $this->writeToInfluxDb(
                'logical_sensor_threshold_lower',
                $threshold->adjusted_value_lowerlimit,
                [
                    'logical_sensor_type' => $this->logical_sensor->type,
                    'logical_sensor'      => $this->logical_sensor->name,
                    'physical_sensor'     => $this->logical_sensor->physical_sensor->name,
                    'terrarium'           => $this->logical_sensor->physical_sensor->terrarium->name
                ]
            );
        }

        if (!is_null($threshold->adjusted_value_upperlimit)) {
            $result = $result && $this->writeToInfluxDb(
                'logical_sensor_threshold_upper',
                $threshold->adjusted_value_upperlimit,
                [
                    'logical_sensor_type'   => $this->logical_sensor->type,
                    'logical_sensor'        => $this->logical_sensor->name,
                    'physical_sensor'       => $this->logical_sensor->physical_sensor->name,
                    'terrarium'             => $this->logical_sensor->physical_sensor->terrarium->name
                ]
            );
        }

        return $result;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function properties()
    {
        return $this->hasMany('App\Property', 'belongsTo_id')->where('belongsTo_type', 'Sensorreading');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function logical_sensor()
    {
        return $this->belongsTo('App\LogicalSensor', 'logical_sensor_id');
    }

    /**
     * @return string
     */
    public function icon()
    {
        return 'clipboard-pulse';
    }

    /**
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function url()
    {
        return url('sensorreadings/' . $this->id);
    }
}
