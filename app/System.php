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

    public static function status()
    {
        return [
            'emergency_stop' => !is_null(Property::where('type', 'SystemProperty')->where('name', 'stop_all_action_sequences')->get()->first()),
        ];
    }

    public static function hasVoiceCapability()
    {
        return !is_null(env('API_AI_ACCESS_TOKEN_' . Auth::user()->lang, null));
    }

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

}
