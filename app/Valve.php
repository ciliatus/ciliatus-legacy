<?php

namespace App;

use App\Events\ValveDeleted;
use App\Events\ValveUpdated;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Valve
 * @package App
 */
class Valve extends CiliatusModel
{
    use Traits\Uuids;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */

    public $incrementing = false;

    /**
     *
     */
    public function delete()
    {
        broadcast(new ValveDeleted($this->id));

        parent::delete();
    }


    /**
     * @param array $options
     * @return bool
     */
    public function save(array $options = [])
    {
        $result = parent::save($options);

        broadcast(new ValveUpdated($this));

        return $result;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function terrarium()
    {
        return $this->belongsTo('App\Terrarium');
    }

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
    public function pump()
    {
        return $this->belongsTo('App\Pump')->with('controlunit');
    }

    /**
     * @return string
     */
    public function icon()
    {
        return 'transform';
    }

    /**
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function url()
    {
        return url('valves/' . $this->id);
    }

}
