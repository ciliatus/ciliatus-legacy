<?php

namespace App\Traits;


use GuzzleHttp\Client;
use Webpatser\Uuid\Uuid;

/**
 * Class SendsApiAiRequests
 * @package App\Traits
 */
trait SendsApiAiRequests
{

    /**
     * @param $query
     * @param null $user
     * @param null $custom_sessionId
     * @return bool|mixed
     * @throws \Exception
     */
    public function sendApiAiRequest($query, $user = null, $custom_sessionId = null) {
        if (!is_null($user)) {
            $locale = $user->locale;
        }
        else {
            $locale = env('LOCALE', 'en');
        }
        $apiai_key = env('API_AI_ACCESS_TOKEN_' . $locale, null);

        if (is_null($apiai_key)) {
            return;
        }

        $sessionId = '';
        if (is_null($custom_sessionId)) {
            if (is_null($user)) {
                $sessionId = Uuid::generate()->string;
            }
            else {
                $sessionId = $user->id;
            }
        }
        else {
            $sessionId = $custom_sessionId;
        }

        $client = new Client();
        $send = [
            'headers' => [
                'Content-Type' => 'application/json;charset=utf-8',
                'Authorization' => 'Bearer ' . $apiai_key
            ],
            'body' => json_encode([
                'query' => [ $query ],
                'lang' => $locale,
                'sessionId' => $sessionId
            ])
        ];

        try {
            $response = $client->post('https://api.api.ai/v1/query?v=20150910', $send);
        }
        catch (\GuzzleHttp\Exception\ClientException $ex) {
            \Log::error($ex->getMessage() . PHP_EOL . $ex->getResponse()->getBody());
            return false;
        }
        return json_decode($response->getBody(),true);
    }

}