<?php

namespace App;

use App\Traits\Uuids;
use App\Traits\WritesToInfluxDb;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Sensorreading
 *
 * @package App
 * @property string $id
 * @property string $sensorreadinggroup_id
 * @property string $logical_sensor_id
 * @property float $rawvalue
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property bool $is_anomaly
 * @property-read \App\LogicalSensor $logical_sensor
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @method static \Illuminate\Database\Query\Builder|\App\Sensorreading whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Sensorreading whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Sensorreading whereIsAnomaly($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Sensorreading whereLogicalSensorId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Sensorreading whereRawvalue($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Sensorreading whereSensorreadinggroupId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Sensorreading whereUpdatedAt($value)
 * @mixin \Eloquent
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
        'sensorreadinggroup_id', 'logical_sensor_id', 'rawvalue', 'created_at'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'is_anomaly' => 'boolean'
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

        return $this->writeToInfluxDb(
            'logical_sensor_readings',
            $this->rawvalue,
            [
                'logical_sensor_type'   => $this->logical_sensor->type,
                'logical_sensor'        => $this->logical_sensor->name,
                'physical_sensor'       => $this->logical_sensor->physical_sensor->name,
                'terrarium'             => $this->logical_sensor->physical_sensor->terrarium->name
            ]
        );
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
        return 'show_chart';
    }

    /**
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function url()
    {
        return url('sensorreadings/' . $this->id);
    }
}
