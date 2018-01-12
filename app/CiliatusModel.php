<?php

namespace App;

use App\Http\Transformers\GenericTransformer;
use App\Repositories\GenericRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

/**
 * Class CiliatusModel
 * @package App
 */
abstract class CiliatusModel extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function files()
    {
        return $this->morphToMany('App\File', 'belongsTo', 'has_files', 'belongsTo_id', 'file_id');
    }

    /**
     * Returns a property belonging to the model matching the criteria
     *
     * @param $type
     * @param $name
     * @param $return_value_only bool: If true the value will be returned instead of the Property object
     * @param $return_null_if_value_null bool: If $return_value_only is true and this is true, return null if value is empty
     * @return mixed
     */
    public function property($type, $name, $return_value_only = false, $return_null_if_value_null = false)
    {
        $property = $this->properties()->where('type', $type)->where('name', $name)->get()->first();
        if ($return_value_only) {
            if (is_null($property)) {
                return null;
            }
            if ($return_null_if_value_null &&
                (strlen($property->value) < 1 || is_null($property->value))) {

                return null;
            }
            return $property->value;
        }
        return $property;
    }

    /**
     * Creates a new property belonging to this object
     *
     * @param $type
     * @param $name
     * @param $value
     * @return mixed
     */
    public function setProperty($type, $name, $value)
    {
        $p = Property::create([
            'belongsTo_type' => end(explode("\\",__CLASS__)),
            'belongsTo_id' => $this->id,
            'type' => $type,
            'name' => $name,
            'value' => $value
        ]);

        return $p;
    }

    /**
     * Sets belongsTo_type and belongsTo_id fields of this model to the appropriate values
     * for $model. If $model is null or empty the fields will be set to null.
     * If this model does not have belongsTo_* fields, the method returns.
     *
     * @param CiliatusModel|null $model
     * @param boolean $save Changes will be saved if true
     */
    public function setBelongsTo(CiliatusModel $model = null, $save = true)
    {
        if (!Schema::hasColumn($this->getTable(), 'belongsTo_type') &&
            !Schema::hasColumn($this->getTable(), 'belongsTo_id')) {
            return;
        }

        if (is_null($model)) {
            $this->belongsTo_type = null;
            $this->belongsTo_id = null;
        }
        else {
            $class_split = explode("\\",get_class($model));
            $this->belongsTo_type = end($class_split);
            $this->belongsTo_id = $model->id;
        }

        if ($save) {
            $this->save();
        }
    }

    /**
     * @return bool|null
     */
    public function delete()
    {
        $this->properties()->delete();
        return parent::delete();
    }

    /**
     * Returns true, if no ModelNotActive property for this model was found
     *
     * @return bool
     */
    public function active()
    {
        return $this->properties()->where('type', 'ModelNotActive')->count() < 1;
    }

    /**
     *
     */
    public function enable()
    {
        $this->properties()->where('type', 'ModelNotActive')->delete();
    }

    /**
     *
     */
    public function disable()
    {
        $class_split = explode("\\",get_class($this));
        Property::create([
            'belongsTo_type' => end($class_split),
            'belongsTo_id' => $this->id,
            'type' => 'ModelNotActive',
            'name' => 'ModelNotActive'
        ]);
    }

    /**
     * @return CiliatusModel
     */
    public function enrich()
    {
        $class_split = explode("\\",get_class($this));
        $class_name = 'App\Repositories\\' . end($class_split) . 'Repository';
        if (class_exists($class_name)) {
            return (new $class_name($this))->show();
        }
        else {
            return (new GenericRepository($this))->show();
        }
    }

    /**
     * @return array
     */
    public function transform()
    {
        $class_split = explode("\\",get_class($this));
        $class_name = 'App\Http\Transformers\\' . end($class_split) . 'Transformer';
        if (class_exists($class_name)) {
            return (new $class_name())->transform($this->toArray());
        }
        else {
            return (new GenericTransformer())->transform($this);
        }
    }

    /**
     * @return Collection
     */
    public function getPossiblyAffectedAnimals()
    {
        if (!is_null($this->animals)) {
            return $this->animals;
        }

        return new Collection();
    }

    /**
     * @return array
     */
    public function enrichAndTransform()
    {
        return $this->enrich()->transform();
    }

    /**
     * @return mixed
     */
    abstract public function properties();
    /**
     * @return string
     */
    abstract public function icon();

    /**
     * @return string
     */
    abstract public function url();
}
