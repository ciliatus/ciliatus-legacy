<?php

namespace App;

use App\Events\PumpDeleted;
use App\Events\PumpUpdated;
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
     * @var array
     */
    private static $states = [
        'Running',
        'Stopped'
    ];

    /**
     * @return array
     */
    public static function states()
    {
        return self::$states;
    }

    /**
     *
     */
    public function delete()
    {
        broadcast(new PumpDeleted($this->id));

        parent::delete();
    }


    /**
     * @param array $options
     * @return bool
     */
    public function save(array $options = [])
    {
        $result = parent::save($options);

        broadcast(new PumpUpdated($this));

        return $result;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function controlunit()
    {
        return $this->belongsTo('App\Controlunit');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function valves()
    {
        return $this->hasMany('App\Valve');
    }

    /**
     * @return string
     */
    public function icon()
    {
        return 'rotate_right';
    }

    /**
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function url()
    {
        return url('pumps/' . $this->id);
    }
}
