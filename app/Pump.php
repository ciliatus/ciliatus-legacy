<?php

namespace App;

use App\Events\PumpDeleted;
use App\Events\PumpUpdated;
use App\Traits\Components;
use App\Traits\Uuids;
use Illuminate\Notifications\Notifiable;

/**
 * Class Pump
 *
 * @package App
 */
class Pump extends CiliatusModel
{
    use Uuids, Components, Notifiable;

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
        'name', 'controlunit_id', 'model'
    ];

    /**
     * @var array
     */
    protected $dispatchesEvents = [
        'updated' => PumpUpdated::class,
        'deleting' => PumpDeleted::class
    ];

    /**
     * @var array
     */
    private static $states = [
        'Running',
        'Stopped'
    ];

    /**
     * @return bool|null
     * @throws \Exception
     */
    public function delete()
    {
        Action::where('target_type', 'Pump')->where('target_id', $this->target_id)->delete();

        return parent::delete();
    }

    /**
     * @return array
     */
    public static function states()
    {
        return self::$states;
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
        return 'water-pump';
    }

    /**
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function url()
    {
        return url('pumps/' . $this->id);
    }
}
