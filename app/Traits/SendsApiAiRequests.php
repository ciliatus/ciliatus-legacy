<?php

namespace App\Traits;


use App\Action;
use App\ActionSequence;
use Auth;
use GuzzleHttp\Client;
use Webpatser\Uuid\Uuid;

/**
 * Class SendsApiAiRequests
 * @package App\Traits
 */
trait SendsApiAiRequests
{

    public function sendApiAiRequest($query, $user = null, $custom_sessionId = null) {
        $apiai_key = env('API_AI_ACCESS_TOKEN', null);
        $locale = env('LOCALE', 'en');

        if (is_null($apiai_key)) {
            return;
        }

        $sessionId = '';
        if (is_null($custom_sessionId)) {
            if (is_null($user)) {
                $sessionId = Uuid::generate();
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