<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Terrarium
 * @package App
 */
class Terrarium extends Model
{
    use Traits\Uuids;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */

    public $incrementing = false;

    /**
     * @return mixed
     */
    public function physical_sensors()
    {
        return $this->hasMany('App\PhysicalSensor', 'belongsTo_id')->where('belongsTo_type', 'terrarium')->with('logical_sensors');
    }

    public function animals()
    {
        return $this->hasMany('App\Animal');
    }
}
