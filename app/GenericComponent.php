<?php

namespace App;

use App\Events\GenericComponentDeleted;
use App\Events\GenericComponentUpdated;
use App\Traits\Components;
use App\Traits\Uuids;
use Illuminate\Notifications\Notifiable;

/**
 * Class GenericComponent
 * @package App
 */
class GenericComponent extends Component
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
        'belongsTo_type', 'belongsTo_id', 'generic_component_type_id', 'name', 'state', 'controlunit_id'
    ];

    /**
     * Overrides Component->notification_type_name
     *
     * @var string
     */
    protected $notification_type_name = 'generic_components';

    /**
     * @var array
     */
    protected $dispatchesEvents = [
        'updated' => GenericComponentUpdated::class,
        'deleting' => GenericComponentDeleted::class
    ];

    /**
     * @return mixed
     */
    public function properties()
    {
        return $this->hasMany('App\Property', 'belongsTo_id')->where('belongsTo_type', 'GenericComponent');
    }

    /**
     * @return mixed
     */
    public function component_properties()
    {
        return $this->hasMany('App\Property', 'belongsTo_id')->where('type', 'GenericComponentProperty');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function states()
    {
        return $this->hasMany('App\Property', 'belongsTo_id')->where('type', 'GenericComponentState');
    }

    /**
     * @return mixed
     */
    public function intentions()
    {
        return $this->type->intentions();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo('App\GenericComponentType', 'generic_component_type_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function controlunit()
    {
        return $this->belongsTo('App\Controlunit');
    }

    /**
     * Removes/Adds component's properties to sync with it's type
     */
    public function resync_properties()
    {
        foreach ($this->type->properties as $type_prop) {
            if (is_null($this->properties()->where('name', $type_prop->name)->get()->first())) {
                Property::create([
                    'belongsTo_type' => 'GenericComponentType',
                    'belongsTo_id' => $this->id,
                    'type' => 'GenericComponentProperty',
                    'name' => $type_prop->name
                ]);
            }
        }

        foreach ($this->properties as $prop) {
            if (is_null($this->type->properties()->where('name', $prop->name)->get()->first())) {
                $prop->delete();
            }
        }
    }

    /**
     * Removes/Adds component's states to sync with it's type
     */
    public function resync_states()
    {
        foreach ($this->type->states as $type_state) {
            if (is_null($this->states()->where('name', $type_state->name)->get()->first())) {
                Property::create([
                    'belongsTo_type' => 'GenericComponentType',
                    'belongsTo_id' => $this->id,
                    'type' => 'GenericComponentState',
                    'name' => $type_state->name
                ]);
            }
        }

        foreach ($this->states as $state) {
            if (is_null($this->type->states()->where('name', $state->name)->get()->first())) {
                $state->delete();
            }
        }
    }

    /**
     * @return string
     */
    public function generateConfig()
    {
        $type_name = preg_replace('/[^a-zA-Z0-9_]|[\s]/', '', $this->type->name_singular);
        $name = preg_replace('/[^a-zA-Z]|[\s]/', '', $this->name);
        $config = "[generic_component_{$name}]\nid = {$this->id}\nname = {$this->name}\ntype = {$type_name}\n";

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
     * Returns the associated GenericComponentTypeIntention property or null.
     *
     * @return null
     */
    public function getDefaultIntention()
    {
        return $this->type->getDefaultIntention();
    }

    /**
     * Returns the associated GenericComponentType property.
     * If that is null, the result of getDefaultIntention() will be returned.
     *
     * @return null
     */
    public function getIntention()
    {
        $intention = $this->properties()->where('type', 'GenericComponentIntention')->get()->first();
        if (is_null($intention)) {
            return $this->getDefaultIntention();
        }

        return $intention;
    }

    /**
     * Returns the associated GenericComponentTypeRunningState property or null.
     *
     * @return null
     */
    public function getDefaultRunningState()
    {
        return $this->type->getDefaultRunningState();
    }

    /**
     * Returns the default state in which this component is running.
     *
     * @return string
     */
    public function getRunningState()
    {
        $running_state = $this->properties()->where('type', 'GenericComponentRunningState')->get()->first();
        if (is_null($running_state)) {
            return $this->getDefaultRunningState();
        }

        return $running_state;
    }

    /**
     * @return string
     */
    public function icon()
    {
        return $this->type->icon;
    }

    /**
     *
     */
    public function url()
    {
        return url('generic_components/' . $this->id);
    }

    /**
     * @param $type
     * @param $locale
     * @return array|\Illuminate\Contracts\Translation\Translator|null|string
     */
    protected function getCriticalStateNotificationsText($type, $locale)
    {
        return trans('messages.'. $type . '_' . $this->notification_type_name, [
            'generic_component_name' => $this->name
        ], $locale);
    }
}
