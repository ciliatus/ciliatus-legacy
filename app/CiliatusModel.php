<?php

namespace App;

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
            $this->belongsTo_type = end(explode("\\",get_class($model)));
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
