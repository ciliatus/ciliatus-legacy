<?php

namespace App;

use App\Events\ValveDeleted;
use App\Events\ValveUpdated;
use App\Traits\Components;
use App\Traits\Uuids;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

/**
 * Class Valve
 * @package App
 */
class Valve extends CiliatusModel
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
    protected $fillable = ['name', 'terrarium_id', 'pump_id', 'controlunit_id', 'model'];

    /**
     * @var array
     */
    private static $states = [
        'Open',
        'Closed'
    ];

    /**
     * @var array
     */
    protected $dispatchesEvents = [
        'updated' => ValveUpdated::class,
        'deleting' => ValveDeleted::class
    ];

    /**
     * @return bool|null
     * @throws \Exception
     */
    public function delete()
    {
        Action::where('target_type', 'Valve')->where('target_id', $this->target_id)->delete();

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
        return $this->hasMany('App\Property', 'belongsTo_id')->where('belongsTo_type', 'Valve');
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
    public function generateConfig()
    {
        $name = preg_replace('/[^a-zA-Z0-9_]|[\s]/', '', $this->name);
        $config = "[valve_{$name}]\nid = {$this->id}\nname = {$this->name}\n";

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
     * @return Collection
     */
    public function getPossiblyAffectedAnimals()
    {
        if (is_null($this->terrarium())) {
            return new Collection();
        }
        return $this->terrarium->animals;
    }

    /**
     * @return string
     */
    public function icon()
    {
        return 'pipe-disconnected';
    }

    /**
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function url()
    {
        return url('valves/' . $this->id);
    }

}
