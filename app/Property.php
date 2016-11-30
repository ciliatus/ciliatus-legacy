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
     * @return string
     */
    public function icon()
    {
        // TODO: Implement icon() method.
    }

    /**
     *
     */
    public function url()
    {
        // TODO: Implement url() method.
    }
}
