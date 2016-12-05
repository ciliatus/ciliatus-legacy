<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Event
 * @package App
 */
class Event extends Model
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
    protected $fillable = [];

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
}
