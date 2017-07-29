<?php

namespace App;

use ApiAi\HttpClient\GuzzleHttpClient;
use App\Traits\WritesToInfluxDb;
use Auth;
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
    public static function status()
    {
        return [
            'emergency_stop' => !is_null(Property::where('type', 'SystemProperty')->where('name', 'stop_all_action_sequences')->get()->first()),
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

}
