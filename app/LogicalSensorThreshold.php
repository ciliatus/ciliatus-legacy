<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LogicalSensorThreshold
 * @package App
 */
class LogicalSensorThreshold extends CiliatusModel
{
    use Traits\Uuids;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */

    public $incrementing = false;

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
        return 'circle-o';
    }

    /**
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function url()
    {
        return url('logical_sensor_thresholds/' . $this->id);
    }
}
