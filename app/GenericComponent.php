<?php

namespace App;

use App\Property;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use InvalidArgumentException;

/**
 * Class GenericComponent
 * @package App
 */
class GenericComponent extends CiliatusModel
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
    protected $fillable = [
        'belongsTo_type', 'belongsTo_id', 'generic_component_type_id', 'name', 'state', 'controlunit_id'
    ];

    /**
     * @return bool|null
     */
    public function delete()
    {
        foreach ($this->properties as $p) {
            $p->delete();
        }

        foreach ($this->states as $s) {
            $s->delete();
        }

        return parent::delete();
    }

    /**
     * @return mixed
     */
    public function properties()
    {
        return $this->hasMany('App\Property', 'belongsTo_id')->where('type', 'GenericComponentProperty');
    }

    /**
     * @return mixed
     */
    public function states()
    {
        return $this->hasMany('App\Property', 'belongsTo_id')->where('type', 'GenericComponentState');
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
     * Removes/Adds component's properties with it's type
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
     * Removes/Adds component's states with it's type
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
        $config = "[generic_component_{$name}]\nid = {$this->id}\npin =\nname = {$this->name}\ntype = {$type_name}";

        return $config;
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
}
