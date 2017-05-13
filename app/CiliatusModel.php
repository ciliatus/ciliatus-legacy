<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Animal
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
            'belongsTo_type' => explode("\\",__CLASS__)[1],
            'belongsTo_id' => $this->id,
            'type' => $type,
            'name' => $name,
            'value' => $value
        ]);

        return $p;
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
