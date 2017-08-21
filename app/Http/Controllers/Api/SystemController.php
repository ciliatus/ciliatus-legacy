<?php

namespace App\Http\Controllers\Api;

use App\System;
use Illuminate\Http\Request;


/**
 * Class SystemController
 * @package App\Http\Controllers
 */
class SystemController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function health(Request $request)
    {
        return $this->respondWithData(System::health());
    }

}
