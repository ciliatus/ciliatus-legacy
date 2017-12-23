<?php

namespace App;

use App\Events\PhysicalSensorDeleted;
use App\Events\PhysicalSensorUpdated;
use App\Traits\Uuids;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;

/**
 * Class PhysicalSensor
 * @package App
 */
class PhysicalSensor extends CiliatusModel
{
    use Uuids, Notifiable;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */

    public $incrementing = false;

    /**
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'heartbeat_at'];

    /**
     * @var array
     */
    protected $fillable = ['name', 'belongsTo_type', 'belongsTo_id', 'controlunit_id'];

    /**
     * @var array
     */
    protected $dispatchesEvents = [
        'updated' => PhysicalSensorUpdated::class,
        'deleting' => PhysicalSensorDeleted::class
    ];

    /**
     * @return bool|null
     */
    public function delete()
    {
        $this->logical_sensors()->delete();
        return parent::delete();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function properties()
    {
        return $this->hasMany('App\Property', 'belongsTo_id')->where('belongsTo_type', 'PhysicalSensor');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|null
     */
    public function terrarium()
    {
         return $this->belongsTo('App\Terrarium', 'belongsTo_id', 'id');
    }

    /**
     * @return null|CiliatusModel
     */
    public function belongsTo_object()
    {
        if (!is_null($this->belongsTo_type) && !is_null($this->belongsTo_id)) {
            $class_name = 'App\\' . ucfirst($this->belongsTo_type);
            if (!class_exists($class_name)) {
                \Log::warning(__CLASS__ . ' "' . $this->name . '" (' . $this->id . ') belongs to object of ' .
                    'unknown class "' . $class_name . '" (' . $this->belongsTo_id . '). Maybe belongsTo is empty but ' .
                    'not null?');
                return null;
            }

            $object = $class_name::find($this->belongsTo_id);
            return $object;
        }

        return null;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logical_sensors()
    {
        return $this->hasMany('App\LogicalSensor');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function controlunit()
    {
        return $this->belongsTo('App\Controlunit');
    }

    /**
     * @return string
     */
    public function generateConfig()
    {
        $lsstr = [];
        foreach ($this->logical_sensors as $ls) {
            $lsstr[] = "{$ls->type}:{$ls->id}";
        }
        $lsstr = implode('|', $lsstr);

        $name = preg_replace('/[^a-zA-Z0-9_]|[\s]/', '', $this->name);
        $config = "[sensor_{$name}]\nid = {$this->id}\nname = {$this->name}\nmodel = {$this->model}\nlogical = {$lsstr}\nenabled = True\n";

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
    public function heartbeatOk()
    {
        return Carbon::now()->diffInMinutes($this->heartbeat_at) < 10 || is_null($this->heartbeat_at);
    }

    /**
     *
     */
    public function heartbeat()
    {
        $this->heartbeat_at = Carbon::now();
        $this->save();
    }

    /**
     * @return bool
     */
    public function check_notifications_enabled()
    {
        if (is_null($this->belongsTo_object()))
            return false;

        return $this->belongsTo_object()->check_notifications_enabled();
    }

    /**
     * @return string
     */
    public function icon()
    {
        return 'memory';
    }

    /**
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function url()
    {
        return url('physical_sensors/' . $this->id);
    }
}
