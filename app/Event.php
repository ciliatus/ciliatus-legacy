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
     * @return string
     */
    public function icon()
    {
        // TODO: Implement icon() method.
    }

    /**
     * @return string
     */
    public function url()
    {
        // TODO: Implement url() method.
    }
}
