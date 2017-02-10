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
        if (Gate::denies('api-list')) {
            return $this->respondUnauthorized();
        }

        $critical_states = CriticalState::query();

        $critical_states = $this->filter($request, $critical_states);

        /*
         * If raw is passed, pagination will be ignored
         * Permission api-list:raw is required
         */
        if ($request->has('raw') && Gate::allows('api-list:raw')) {

            return $this->setStatusCode(200)->respondWithData(
                $this->critical_stateTransformer->transformCollection(
                    $critical_states->get()->toArray()
                )
            );
        }

        $critical_states = $critical_states->paginate(env('PAGINATION_PER_PAGE', 20));

        return $this->setStatusCode(200)->respondWithPagination(
            $this->critical_stateTransformer->transformCollection(
                $critical_states->toArray()['data']
            ),
            $critical_states
        );

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
