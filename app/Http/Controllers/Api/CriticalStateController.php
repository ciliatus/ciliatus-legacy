<?php

namespace App\Http\Controllers\Api;

use App\Animal;
use App\CriticalState;
use App\Events\ActionSequenceScheduleUpdated;
use App\Events\AnimalFeedingScheduleUpdated;
use App\Http\Transformers\CriticalStateTransformer;
use App\LogicalSensor;
use App\Terrarium;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;

use App\Http\Requests;
use Symfony\Component\Debug\Exception\FatalThrowableError;

/**
 * Class CriticalStateController
 * @package App\Http\Controllers
 */
class CriticalStateController extends ApiController
{

    /**
     * @var CriticalStateTransformer
     */
    protected $critical_stateTransformer;

    /**
     * CriticalStateController constructor.
     * @param CriticalStateTransformer $_critical_stateTransformer
     */
    public function __construct(CriticalStateTransformer $_critical_stateTransformer)
    {
        parent::__construct();
        $this->critical_stateTransformer = $_critical_stateTransformer;
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
