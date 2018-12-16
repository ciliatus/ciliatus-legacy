<?php

namespace App;

use App\Traits\Uuids;
use Carbon\Carbon;

/**
 * Class LogicalSensorThreshold
 * @package App
 */
class LogicalSensorThreshold extends CiliatusModel
{
    use Uuids;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */

    public $incrementing = false;

    /**
     * @var array
     */
    protected $casts = [
        'active'    =>  'boolean'
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'logical_sensor_id', 'starts_at', 'adjusted_value_lowerlimit', 'adjusted_value_upperlimit', 'active'
    ];

    /**
     * @param array $options
     * @return bool
     */
    public function save(array $options = [])
    {
        if (!in_array('no_new_name', $options)) {
            $this->name = 'LSTH_';
            if (!is_null($this->logical_sensor)) {
                $this->name .= $this->logical_sensor->name;
            } else {
                $this->name .= $this->id;
            }
            $this->name .= '_' . Carbon::parse($this->starts_at)->format('h:i:s');
            $this->save(['no_new_name']);
        }

        return parent::save($options);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function properties()
    {
        return $this->hasMany('App\Property', 'belongsTo_id')->where('belongsTo_type', 'LogicalSensorThreshold');
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
        return 'format-vertical-align-center';
    }

    /**
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function url()
    {
        return url('logical_sensor_thresholds/' . $this->id);
    }
}
