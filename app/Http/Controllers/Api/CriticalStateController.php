<?php

namespace App\Http\Controllers\Api;

use App\CriticalState;
use Gate;
use Illuminate\Http\Request;

/**
 * Class CriticalStateController
 * @package App\Http\Controllers
 */
class CriticalStateController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return parent::default_index($request);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        return parent::default_show($request, $id);
    }
    
    /**
     *
     */
    public function evaluate()
    {

        if (Gate::denies('api-evaluate:critical_state')) {
            return $this->respondUnauthorized();
        }

        CriticalState::evaluate();

        return $this->respondWithData([]);
    }

}
