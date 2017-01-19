<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function belongsTo_object()
    {
        return $this->belongsTo('App\\'. $this->belongsTo_type, 'belongsTo_id');
    }

    /**
     * @return mixed
     */
    public function properties()
    {
        return $this->hasMany('App\Property', 'belongsTo_id')->where('belongsTo_type', 'Event');
    }

    /**
     * @param $name
     * @return Property|null
     */
    public function property($name)
    {
        return $this->properties()->where('name', $name)->limit(1)->get()->first();
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
        return 'event';
    }

    /**
     * @return string
     */
    public function url()
    {
        return url('events/' . $this->id);
    }
}
