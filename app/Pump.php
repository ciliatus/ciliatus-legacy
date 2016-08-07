<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Pump
 * @package App
 */
class Pump extends CiliatusModel
{
    use Traits\Uuids;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */

    public $incrementing = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function controlunit()
    {
        return $this->belongsTo('App\Controlunit');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function valves()
    {
        return $this->belongsTo('App\Valves');
    }

    /**
     * @return string
     */
    public function icon()
    {
        return 'circle-o';
    }

    public function url()
    {
        return url('pumps/' . $this->id);
    }
}
