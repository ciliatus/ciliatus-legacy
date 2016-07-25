<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Valve
 * @package App
 */
class Valve extends Model
{
    use Traits\Uuids;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */

    public $incrementing = false;

    public function terrarium()
    {
        return $this->belongsTo('App\Terrarium');
    }

    public function controlunit()
    {
        return $this->belongsTo('App\Controlunit');
    }

    public function pump()
    {
        return $this->belongsTo('App\Pump');
    }

}
