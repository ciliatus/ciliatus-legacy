<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Property
 * @package App
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
