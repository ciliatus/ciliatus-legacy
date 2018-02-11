<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\System;
use Composer\Semver\Comparator;
use Gate;
use GuzzleHttp\Client;

/**
 * Class SystemController
 * @package App\Http\Controllers\Web
 */
class SystemController extends Controller
{
    /**
     * @var
     */
    protected $request;

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     * @throws \Exception
     */
    public function index()
    {

        if (Gate::denies('admin')) {
            return response()->view('errors.401', [], 401);
        }

        $client = new Client();
        $send = [
            'headers' => [
                'Content-Type' => 'application/json;charset=utf-8'
            ]
        ];

        try {
            $response = $client->get('https://api.github.com/repos/ciliatus/ciliatus/releases/latest', $send);
            $version = json_decode($response->getBody(),true);
        }
        catch (\GuzzleHttp\Exception\ClientException $ex) {
            \Log::error($ex->getMessage() . PHP_EOL . $ex->getResponse()->getBody());
            $version = false;
        }

        return view('system.status', [
            'influx_db_status' => System::influxDbConfigurationStatus(),
            'api_ai_status' => System::apiAiConfigurationStatus(),
            'version' => [
                'data' => $version,
                'current' => $version !== false && Comparator::greaterThanOrEqualTo(config('app.version'), $version['tag_name'])
            ]
        ]);

    }
}
