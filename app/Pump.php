<?php

namespace App;

use App\Events\PumpDeleted;
use App\Events\PumpUpdated;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Pump
 *
 * @package App
 * @property string $id
 * @property string $controlunit_id
 * @property string $name
 * @property string $state
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Controlunit $controlunit
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Valve[] $valves
 * @method static \Illuminate\Database\Query\Builder|\App\Pump whereControlunitId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Pump whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Pump whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Pump whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Pump whereState($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Pump whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Pump extends CiliatusModel
{
    use Traits\Uuids, Traits\Components;

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
        'name', 'controlunit_id'
    ];

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

        foreach ($this->valves as $v) {
            $v->pump_id = null;
            $v->save();
        }

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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function properties()
    {
        return $this->hasMany('App\Property', 'belongsTo_id')->where('belongsTo_type', 'Pump');
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
    public function generateConfig()
    {
        $name = preg_replace('/[^a-zA-Z0-9_]|[\s]/', '', $this->name);
        $config = "[pump_{$name}]\nid = {$this->id}\nname = {$this->name}\n";

        if ($this->property('ControlunitConnectivity', 'bus_type', true) == 'gpio') {
            $config .= "pin = {$this->property('ControlunitConnectivity', 'gpio_pin', true)}\n";
            if (!is_null($this->property('ControlunitConnectivity', 'gpio_default_high', true)) &&
                $this->property('ControlunitConnectivity', 'gpio_default_high', true)) {
                $config .= "default_high = True\n";
            }
        }
        elseif ($this->property('ControlunitConnectivity', 'bus_type', true) == 'i2c') {
            $config .= "i2c_address = {$this->property('ControlunitConnectivity', 'i2c_address', true)}\n";
            if (!is_null($this->property('ControlunitConnectivity', 'i2c_multiplexer_address', true, true))) {
                $config .= "i2c_multiplexer_address = {$this->property('ControlunitConnectivity', 'i2c_multiplexer_address', true)}\n";
                $config .= "i2c_multiplexer_port = {$this->property('ControlunitConnectivity', 'i2c_multiplexer_port', true)}\n";
            }
        }

        return $config;
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
