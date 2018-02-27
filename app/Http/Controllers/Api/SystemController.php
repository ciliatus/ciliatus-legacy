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
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->errorCodeNamespace = '2A';
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
