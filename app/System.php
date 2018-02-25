<?php

namespace App;

use ApiAi\HttpClient\GuzzleHttpClient;
use App\Traits\WritesToInfluxDb;
use Auth;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

/**
 * Class System
 *
 * @package App
 * @mixin \Eloquent
 */
class System extends Model
{

    use WritesToInfluxDb;

    /**
     * @return array
     */
    public static function health()
    {

        $t_24h = Carbon::now();
        $t_24h->subHours(24);
        $t_1h = Carbon::now();
        $t_1h->subHours(1);
        $t_30m = Carbon::now();
        $t_30m->subMinutes(30);
        $t_15m = Carbon::now();
        $t_15m->subMinutes(15);
        $t_5m = Carbon::now();
        $t_5m->subMinutes(5);

        $controlunits = [];
        foreach (Controlunit::get() as $cu) {
            $controlunits[$cu->id] = [
                'id' => $cu->id,
                'name' => $cu->name,
                'version' => $cu->software_version,
                'active' => $cu->active(),
                'heartbeat' => [
                    'last' => $cu->heartbeat_at,
                    'diff_seconds' => Carbon::now()->diffInSeconds($cu->heartbeat_at)
                ],
                'client_server_time_diff_seconds' => $cu->client_server_time_diff_seconds
            ];
        }

        if (env('ENABLE_REQUEST_LOGGING', false)) {
            $execution_time = [
                'avg_exec_time_30m' => LogRequest::averageExecutionTime($t_30m),
                'avg_exec_time_15m' => LogRequest::averageExecutionTime($t_15m),
                'avg_exec_time_5m' => LogRequest::averageExecutionTime($t_5m),
                'min_exec_time_30m' => LogRequest::minExecutionTime($t_30m),
                'min_exec_time_15m' => LogRequest::minExecutionTime($t_15m),
                'min_exec_time_5m' => LogRequest::minExecutionTime($t_5m),
                'max_exec_time_30m' => LogRequest::maxExecutionTime($t_30m),
                'max_exec_time_15m' => LogRequest::maxExecutionTime($t_15m),
                'max_exec_time_5m' => LogRequest::maxExecutionTime($t_5m)
            ];

            $endpoints = [
                'top5_execution_time' => [
                    '30m' => LogRequest::topRequests(5, $t_30m),
                    '15m' => LogRequest::topRequests(5, $t_15m),
                    '5m' => LogRequest::topRequests(5, $t_5m)
                ]
            ];
        }
        else {
            $execution_time = [];
            $endpoints = [];
        }

        $health = [
            'version' => config('app.version'),
            'requests' => [
                'execution_time' => $execution_time,
                'endpoints' => $endpoints
            ],
            'throughput' => [
                'sensorreadings' => [
                    'received_24h' => Sensorreading::query()->where('created_at', '>', $t_24h)->count(),
                    'received_30m' => Sensorreading::query()->where('created_at', '>', $t_30m)->count(),
                ],
                'notifications' => [
                    'sent_24h' => Message::query()->where('created_at', '>', $t_24h)->where('state', 'sent')->count(),
                    'draft_24h' => Message::query()->where('created_at', '>', $t_24h)->where('state', 'draft')->count(),
                    'other_24h' => Message::query()->where('created_at', '>', $t_24h)->whereNotIn('state', ['draft', 'sent'])->count(),
                    'sent_30m' => Message::query()->where('created_at', '>', $t_30m)->where('state', 'sent')->count(),
                    'draft_30m' => Message::query()->where('created_at', '>', $t_30m)->where('state', 'draft')->count(),
                    'other_30m' => Message::query()->where('created_at', '>', $t_30m)->whereNotIn('state', ['draft', 'sent'])->count(),
                ]
            ],
            'controlunits' => $controlunits
        ];

        return $health;
    }

    /**
     * @return array
     */
    public static function status()
    {
        return [
            'emergency_stop' => !is_null(System::property('stop_all_action_sequences')),
        ];
    }

    /**
     * @return bool
     */
    public static function hasVoiceCapability()
    {
        return !empty(env('API_AI_ACCESS_TOKEN_' . Auth::user()->lang, ''));
    }

    /**
     * @return bool
     */
    public static function hasInfluxDbCapability()
    {
        return !empty(env('INFLUX_DB', '')) &&
               !empty(env('INFLUX_HOST', '')) &&
               !empty(env('INFLUX_USER', ''));
    }

    /**
     * @return array
     * @throws \Exception
     */
    public static function apiAiConfigurationStatus()
    {
        $test_data = [
            'you'       =>  is_null(env('API_AI_ACCESS_TOKEN_' . Auth::user()->lang, null)),
            'default'   =>  is_null(env('API_AI_ACCESS_TOKEN_' . app()->getLocale(), null)),
            'attempt'  =>  false
        ];

        if (is_null($test_data['you']) && is_nulL($test_data['default'])) {
            return $test_data;
        }

        $locale = $test_data['you'] ? Auth::user()->locale : app()->getLocale();
        $test_token = env('API_AI_ACCESS_TOKEN_' . $locale);

        try {
            $client = new Client();
            $response = $client->post('https://api.api.ai/v1/query?v=20150910',
                [
                    'headers' => [
                        'Content-Type' => 'application/json;charset=utf-8',
                        'Authorization' => 'Bearer ' . $test_token
                    ],
                    'body' => json_encode([
                        'query' => [ 'hello' ],
                        'lang' => $locale,
                        'sessionId' => Uuid::generate()->string
                    ])
                ]
            );
        }
        catch (\GuzzleHttp\Exception\ClientException $ex) {
            $test_data['attempt'] = $ex->getMessage() . PHP_EOL . $ex->getResponse()->getBody();
            return $test_data;
        }

        switch ($response->getStatusCode()) {
            case 200:
                $test_data['attempt'] = true;
                break;
            case 401:
                $test_data['attempt'] = 'Unauthorized';
                break;
            case 422:
                $test_data['attempt'] = 'Unprocessable Entity';
                break;
            case 500:
                $test_data['attempt'] = 'Server Error';
                break;
        }

        return $test_data;
    }

    /**
     * @return bool|string
     */
    public static function influxDbConfigurationStatus()
    {
        $client = null;
        $database = null;

        try {
            $client = self::getInfluxClient(self::getInfluxConfig());
        }
        catch (\Exception $ex) {
            return $ex->getMessage();
        }

        try {
            $database = self::getInfluxDatabase($client, self::getInfluxConfig());
        }
        catch (\Exception $ex) {
            return $ex->getMessage();
        }

        try {
            $result = $database->listRetentionPolicies();
        }
        catch (\Exception $ex) {
            return $ex->getMessage();
        }

        return true;

    }

    /**
     * @return mixed
     */
    public static function maxUploadFileSize()
    {
        $max_size = self::parseSize(ini_get('post_max_size'));
        $upload_max = self::parseSize(ini_get('upload_max_filesize'));

        if ($upload_max > 0 && $upload_max < $max_size) {
            $max_size = $upload_max;
        }

        return $max_size;
    }

    /**
     * @param $size
     * @return float
     */
    public static function parseSize($size) {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
        $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
        if ($unit) {
            // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        }
        else {
            return round($size);
        }
    }

    /**
     * @param $name
     * @return mixed
     */
    public static function property($name)
    {
        return Property::where('type', 'SystemProperty')
                       ->where('name', $name)
                       ->get()
                       ->first();
    }

}
