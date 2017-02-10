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
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if (Gate::denies('api-list')) {
            return $this->respondUnauthorized();
        }

        $logs = Log::query();

        $logs = $this->filter($request, $logs);

        /*
         * If raw is passed, pagination will be ignored
         * Permission api-list:raw is required
         */
        if ($request->has('raw') && Gate::allows('api-list:raw')) {
            $logs = $logs->get();

            foreach ($logs as &$l) {
                $l->source = (new GenericTransformer())->transform((new GenericRepository($l->source()->get()->first()))->show());
                $l->target = (new GenericTransformer())->transform((new GenericRepository($l->target()->get()->first()))->show());
                $l->associated = (new GenericTransformer())->transform((new GenericRepository($l->associated()->get()->first()))->show());
            }

            return $this->setStatusCode(200)->respondWithData(
                $this->logTransformer->transformCollection(
                    $logs->toArray()
                )
            );

        }

        $logs = $logs->paginate(env('PAGINATION_PER_PAGE', 20));

        foreach ($logs->items() as &$l) {
            if (!is_null($l->source_type) && !is_null($l->source_id)) {
                $l->source = (new GenericTransformer())->transform((new GenericRepository($l->source()->get()->first()))->show());

            }

            if (!is_null($l->target_type) && !is_null($l->target_id)) {
                $l->target = (new GenericTransformer())->transform((new GenericRepository($l->target()->get()->first()))->show());
            }

            if (!is_null($l->associatedWith_type) && !is_null($l->associatedWith_type)) {
                $l->associated = (new GenericTransformer())->transform((new GenericRepository($l->associated()->get()->first()))->show());
            }
        }

        return $this->setStatusCode(200)->respondWithPagination(
            $this->logTransformer->transformCollection(
                $logs->toArray()['data']
            ),
            $logs
        );

    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {

        if (Gate::denies('api-read')) {
            return $this->respondUnauthorized();
        }

        $log = Log::with('source_object')
                    ->with('target_object')
                    ->with('associated')
                    ->find($id);

        if (!$log) {
            return $this->respondNotFound('Log not found');
        }

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
