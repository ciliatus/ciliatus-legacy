<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Pump
 * @package App
 */
class Pump extends Model
{
    use Traits\Uuids;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */

    public $incrementing = false;

    public function controlunit()
    {
        return $this->belongsTo('App\Controlunit');
    }

    public function valves()
    {
        return $this->belongsTo('App\Valves');
    }

}
