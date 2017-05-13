<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Event
 *
 * @package App
 * @property string $id
 * @property string $belongsTo_type
 * @property string $belongsTo_id
 * @property string $type
 * @property string $name
 * @property string $value
 * @property string $value_json
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @method static \Illuminate\Database\Query\Builder|\App\Event whereBelongsToId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Event whereBelongsToType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Event whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Event whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Event whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Event whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Event whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Event whereValueJson($value)
 * @mixin \Eloquent
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
        if (is_null($this->belongsTo_type)) {
            return null;
        }
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
