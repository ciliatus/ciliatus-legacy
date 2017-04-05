<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use phpDocumentor\Reflection\DocBlock\Tags\Generic;

/**
 * Class GenericComponentType
 * @package App
 */
class GenericComponentType extends CiliatusModel
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
        'name_singular', 'name_plural', 'icon'
    ];

    /**
     * @return bool|null
     */
    public function delete()
    {
        foreach ($this->properties as $p) {
            $p->delete();
        }

        foreach ($this->components as $c) {
            $c->delete();
        }

        foreach ($this->states as $s) {
            $s->delete();
        }

        foreach ($this->intentions as $i) {
            $i->delete();
        }

        return parent::delete();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function properties()
    {
        return $this->hasMany('App\Property', 'belongsTo_id')->where('belongsTo_type', 'GenericComponentType')
            ->where('type', 'GenericComponentTypeProperty');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function states()
    {
        return $this->hasMany('App\Property', 'belongsTo_id')->where('belongsTo_type', 'GenericComponentType')
                                                             ->where('type', 'GenericComponentTypeState');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function intentions()
    {
        return $this->hasMany('App\Property', 'belongsTo_id')->where('belongsTo_type', 'GenericComponentType')
            ->where('type', 'GenericComponentTypeIntention');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function components()
    {
        return $this->hasMany('App\GenericComponent');
    }

    /**
     * Returns the associated GenericComponentTypeIntention property or null
     *
     * @return null
     */
    public function getDefaultIntention()
    {
        $intention = $this->properties()->where('type', 'GenericComponentTypeIntention')->get()->first();
        if (is_null($intention)) {
            return null;
        }

        return $intention;
    }

    /**
     * Returns the associated GenericComponentTypeRunningState property or null
     *
     * @return null|String
     */
    public function getDefaultRunningState()
    {
        $running_state = Property::find($this->default_running_state_id);
        if (is_null($running_state)) {
            return null;
        }

        return $running_state->name;
    }


    /**
     * Returns all generic components with the defined intention.
     *
     * @param $value
     * @param $intention
     * @param Builder|null $scope
     * @return \Illuminate\Support\Collection
     */
    public static function getGenericComponentsByIntention($value, $intention, Builder $scope = null)
    {
        if (is_null($scope)) {
            $scope = GenericComponent::query();
        }

        $components = $scope->get();
        $matched = new Collection();
        foreach ($components as $component) {
            if (!is_null($component->intentions()->where('name', $intention)->where('value', $value)->get()->first())) {
                $matched->push($component);
            }
        }

        return $matched;

    }

    /**
     * @return string
     */
    public function icon()
    {
        // TODO: Implement icon() method.
    }

    /**
     * @return string
     */
    public function url()
    {
        // TODO: Implement url() method.
    }
}
