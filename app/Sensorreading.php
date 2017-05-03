<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Sensorreading
 * @package App
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
