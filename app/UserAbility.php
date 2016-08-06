<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserAbility
 * @package App
 */
class UserAbility extends Model
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
        'user_id'
    ];

    /**
     * @var array
     */
    protected static $abilities = [
        'grant_api-list',
        'grant_api-read',
        'grant_api-write:animal',
        'grant_api-write:terrarium',
        'grant_api-write:sensorreading',
        'grant_api-write:pump',
        'grant_api-write:valve',
        'grant_api-write:physical_sensor',
        'grant_api-write:logical_sensor',
        'grant_api-write:controlunit',
        'grant_api-write:file',
        'grant_api-write:file_property'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * @return array
     */
    public static function abilities()
    {
        return self::$abilities;
    }

}
