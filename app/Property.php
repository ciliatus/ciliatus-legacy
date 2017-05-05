<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Property
 *
 * @package App
 * @property string $id
 * @property string $belongsTo_type
 * @property string $belongsTo_id
 * @property string $type
 * @property string $name
 * @property bool $value
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @method static \Illuminate\Database\Query\Builder|\App\Property whereBelongsToId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Property whereBelongsToType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Property whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Property whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Property whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Property whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Property whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Property whereValue($value)
 * @mixin \Eloquent
 */
class Property extends CiliatusModel
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
        'name', 'value'
    ];

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
