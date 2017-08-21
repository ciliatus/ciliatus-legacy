<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\LogRequest
 *
 * @mixin \Eloquent
 */
class LogRequest extends Model
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
        'endpoint', 'protocol', 'remote_address', 'user_agent', 'referrer', 'method', 'http_status',
        'geo_iso_code', 'geo_country', 'geo_city', 'geo_postal_code', 'geo_lat', 'geo_lon',
        'duration_ms', 'request_time'
    ];

}