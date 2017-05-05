<?php

namespace App;

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
    use Traits\Uuids;

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
        'sensorreadinggroup_id', 'logical_sensor_id', 'rawvalue'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'is_anomaly' => 'boolean'
    ];

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
        return $this->belongsTo('App\LogicalSensor');
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
