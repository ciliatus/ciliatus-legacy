<?php

namespace App\Http\Controllers\Api;

use App\Http\Transformers\LogTransformer;
use App\Log;
use Gate;
use Illuminate\Http\Request;


/**
 * Class LogController
 * @package App\Http\Controllers
 */
class LogController extends ApiController
{

    /**
     * LogController constructor.
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \ErrorException
     */
    public function index(Request $request)
    {
        return parent::default_index($request);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {

        if (Gate::denies('api-read')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var Log $log
         */
        $log = Log::find($id);

        if (is_null($log)) {
            return $this->respondNotFound('Log not found');
        }

        $log->addSourceTargetAssociated(true);

        return $this->setStatusCode(200)->respondWithData(
            (new LogTransformer())->transform(
                $log->toArray()
            )
        );
    }

    /**
     * @param Request $request
     * @param $id
     * @return void
     */
    public function destroy(Request $request, $id)
    {

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        if (Gate::denies('api-write:log')) {
            return $this->respondUnauthorized();
        }

    }

    /**
     * @param Request $request
     * @return void
     */
    public function update(Request $request)
    {

    }

}
