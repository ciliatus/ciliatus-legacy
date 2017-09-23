<?php

namespace App;

use Carbon\Carbon;
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


    /**
     * @param Carbon|null $from Default 30 minutes ago
     * @param Carbon|null $to Default now
     * @param null $endpoint
     * @return $this
     */
    public static function executionTime(Carbon $from = null, Carbon $to = null, $endpoint = null)
    {

        if (is_null($from)) {
            $from = Carbon::now();
            $from->subMinutes(30);
        }

        if (is_null($to)) {
            $to = Carbon::now();
        }

        $data = self::query()
                    ->where('request_time', '>', $from)
                    ->where('request_time', '<', $to);

        if (!is_null($endpoint)) {
            $data = $data->where('endpoint', 'like', '%' . $endpoint . '%');
        }

        return $data;

    }

    /**
     * Returns the average execution time between the given timestamps.
     * If no timestamps are set, the last 5 minutes will be retrieved.
     *
     * @param Carbon $from
     * @param Carbon|null $to
     * @param null $endpoint
     * @return integer|null
     */
    public static function averageExecutionTime(Carbon $from = null, Carbon $to = null, $endpoint = null)
    {
        return self::executionTime($from, $to, $endpoint)->avg('duration_ms');;
    }

    /**
     * Returns the maximum execution time between the given timestamps.
     *
     * @param Carbon $from
     * @param Carbon|null $to
     * @param null $endpoint
     * @return integer|null
     */
    public static function maxExecutionTime(Carbon $from = null, Carbon $to = null, $endpoint = null)
    {
        return self::executionTime($from, $to, $endpoint)->max('duration_ms');;
    }

    /**
     * Returns the minimum execution time between the given timestamps.
     *
     * @param Carbon $from
     * @param Carbon|null $to
     * @param null $endpoint
     * @return integer|null
     */
    public static function minExecutionTime(Carbon $from = null, Carbon $to = null, $endpoint = null)
    {
        return self::executionTime($from, $to, $endpoint)->min('duration_ms');;
    }

    /**
     * @param $amount
     * @param Carbon|null $from
     * @param Carbon|null $to
     * @param null $endpoint
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function topRequests($amount = 5, Carbon $from = null, Carbon $to = null, $endpoint = null)
    {
        return self::executionTime($from, $to, $endpoint)->orderBy('duration_ms', 'desc')->limit($amount)->get();
    }

}