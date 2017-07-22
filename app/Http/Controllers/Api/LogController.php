<?php

namespace App\Http\Controllers\Api;

use App\Http\Transformers\GenericTransformer;
use App\Log;
use App\Property;
use App\Http\Transformers\LogTransformer;
use App\Repositories\GenericRepository;
use Auth;
use Carbon\Carbon;
use ErrorException;
use Gate;
use \Illuminate\Http\Request;


/**
 * Class LogController
 * @package App\Http\Controllers
 */
class LogController extends ApiController
{
    /**
     * @var LogTransformer
     */
    protected $logTransformer;

    /**
     * LogController constructor.
     * @param LogTransformer $_logTransformer
     */
    public function __construct(LogTransformer $_logTransformer)
    {
        parent::__construct();
        $this->logTransformer = $_logTransformer;
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

        if (Gate::denies('api-read')) {
            return $this->respondUnauthorized();
        }

        $log = Log::find($id);

        if (is_null($log)) {
            return $this->respondNotFound('Log not found');
        }

        $log->addSourceTargetAssociated(true);

        return $this->setStatusCode(200)->respondWithData(
            $this->logTransformer->transform(
                $log->toArray()
            )
        );
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        if (Gate::denies('api-write:log')) {
            return $this->respondUnauthorized();
        }

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {

    }

}
