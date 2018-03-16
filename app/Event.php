<?php

namespace App;

/**
 * Class Event
 * @package App
 */
class Event extends CiliatusModel
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
        'belongsTo_type', 'belongsTo_id', 'type',
        'name', 'value', 'value_json'
    ];

    /**
     * @var array
     */
    protected $casts = [];

    /**
     * @var array
     */
    protected $dates = [];

    /**
     * Models the File can belong to
     *
     * @var array
     */
    protected static $belongTo_Types = [
        'Animal'
    ];

    /**
     * @var array
     */
    public static $available_types = [
        'AnimalFeeding' => [
            'belongsTo_type' => 'Animal'
        ]
    ];

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
     * @return mixed
     */
    public function properties()
    {
        return $this->hasMany('App\Property', 'belongsTo_id')->where('belongsTo_type', 'Event');
    }

    /**
     * @param $type
     * @return mixed
     */
    public function propertyOfType($type)
    {
        return $this->properties()->where('type', $type)->limit(1)->get()->first();
    }

    /**
     * @param $type
     * @param $name
     * @param null $value
     * @return CiliatusModel|Property
     */
    public function create_property($type, $name, $value = null)
    {
        return Property::create([
            'belongsTo_type' => 'Event',
            'belongsTo_id' => $this->id,
            'type' => $type,
            'name' => $name,
            'value' => $value
        ]);
    }

    /**
     * @return array
     */
    public static function belongTo_Types()
    {
        return self::$belongTo_Types;
    }

    /**
     * @return string
     */
    public function icon()
    {
        return 'calendar';
    }

    /**
     * @return string
     */
    public function url()
    {
        return url('events/' . $this->id);
    }
}
