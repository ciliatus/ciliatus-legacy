<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
    public function components()
    {
        return $this->hasMany('App\GenericComponent');
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
