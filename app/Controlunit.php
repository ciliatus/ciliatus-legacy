<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Controlunit
 * @package App
 */
class Controlunit extends Model
{
    use Traits\Uuids;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */

    public $incrementing = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function physical_sensors()
    {
        return $this->hasMany('App\PhysicalSensor');
    }

    /**
     * @return string
     */
    public function heartbeatOk()
    {
        return 'OK';
    }

}
