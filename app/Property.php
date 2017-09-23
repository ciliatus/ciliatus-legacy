<?php

namespace App;

use App\Traits\Uuids;

/**
 * Class Property
 * @package App
 */
class Property extends CiliatusModel
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
        'belongsTo_type', 'belongsTo_id', 'type',
        'name', 'value'
    ];

    /**
     * @var array
     */
    protected static $belongTo_Types = [
        'BiographyEntry' => [
            'Terrarium', 'Animal'
        ]
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function properties()
    {
        return $this->hasMany('App\Property', 'belongsTo_id')->where('belongsTo_type', 'Property');
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
     * @param $value
     * @return bool
     */
    public function getValueAttribute($value)
    {
        switch ($value) {
            case 'true': return true;
            case 'false': return false;
            default: return $value;
        }
    }

    /**
     * Return all object types that can belong
     * to a Property of type $property_type
     *
     * @param String $property_type
     * @return array
     */
    public static function belongTo_Types($property_type)
    {
        return self::$belongTo_Types[$property_type];
    }

    /**
     * @return string
     */
    public function icon()
    {
        return 'local_offer';
    }

    /**
     *
     */
    public function url()
    {
        return '';
    }
}
