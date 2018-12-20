<?php

namespace App;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Class CustomComponentType
 * @package App
 */
class CustomComponentType extends CiliatusModel
{

    use Uuids;

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
     * @throws \Exception
     */
    public function delete()
    {
        $this->properties()->delete();
        $this->states()->delete();
        $this->intentions()->delete();
        $this->components()->delete();

        return parent::delete();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function properties()
    {
        return $this->hasMany('App\Property', 'belongsTo_id')
            ->where('belongsTo_type', 'CustomComponentType')
            ->where('type', 'CustomComponentTypeProperty');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function states()
    {
        return $this->hasMany('App\Property', 'belongsTo_id')
            ->where('belongsTo_type', 'CustomComponentType')
            ->where('type', 'CustomComponentTypeState');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function intentions()
    {
        return $this->hasMany('App\Property', 'belongsTo_id')
            ->where('belongsTo_type', 'CustomComponentType')
            ->where('type', 'CustomComponentTypeIntention');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function components()
    {
        return $this->hasMany('App\CustomComponent');
    }

    /**
     * Returns the associated CustomComponentTypeIntention property or null
     *
     * @return null
     */
    public function getDefaultIntention()
    {
        $intention = $this->properties()->where('type', 'CustomComponentTypeIntention')->get()->first();
        if (is_null($intention)) {
            return null;
        }

        return $intention;
    }

    /**
     * Returns the associated CustomComponentTypeRunningState property or null
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
     * @param $parameter
     * @param $intention
     * @param Builder|null $scope
     * @return \Illuminate\Support\Collection
     */
    public static function getCustomComponentsByIntention($parameter, $intention, Builder $scope = null)
    {
        if (is_null($scope)) {
            $scope = CustomComponent::query();
        }

        $components = $scope->get();
        $matched = new Collection();
        foreach ($components as $component) {
            if (!$component->active()) {
                continue;
            }

            if (!is_null($component->intentions()->where('name', $parameter)->where('value', $intention)->get()->first())) {
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
        return $this->icon;
    }

    /**
     * @return string
     */
    public function url()
    {
        // TODO: Implement url() method.
    }
}
