<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\System;
use Gate;

class SystemController extends Controller
{
    protected $request;

    public function index()
    {

        if (Gate::denies('admin')) {
            return view('errors.401');
        }

        return view('system.status', [
            'influx_db_status' => System::influxDbConfigurationStatus(),
            'api_ai_status' => System::apiAiConfigurationStatus()
        ]);

    }
}
