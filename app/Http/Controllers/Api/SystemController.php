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

    /**
     * SystemController constructor.
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function health(Request $request)
    {
        return $this->respondWithData(System::health());
    }

}
