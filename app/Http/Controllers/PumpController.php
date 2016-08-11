<?php

namespace App\Http\Controllers;

use App\Controlunit;
use App\Http\Transformers\PumpTransformer;
use App\Pump;
use App\Terrarium;
use App\Valve;
use Gate;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;


/**
 * Class PumpController
 * @package App\Http\Controllers
 */
class PumpController extends ApiController
{
    /**
     * @var PumpTransformer
     */
    protected $pumpTransformer;

    /**
     * PumpController constructor.
     * @param PumpTransformer $_pumpTransformer
     */
    public function __construct(PumpTransformer $_pumpTransformer)
    {
        parent::__construct();
        $this->pumpTransformer = $_pumpTransformer;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        if (Gate::denies('api-list')) {
            return $this->respondUnauthorized();
        }

        $pumps = Pump::paginate(10);

        return $this->setStatusCode(200)->respondWithPagination($this->pumpTransformer->transformCollection($pumps->toArray()['data']), $pumps);
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

        $pump = Pump::with('physical_sensors', 'pumps')->find($id);

        if (!$pump) {
            return $this->respondNotFound('Pump not found');
        }

        return $this->setStatusCode(200)->respondWithData(
            $this->pumpTransformer->transform(
                $pump->toArray()
            )
        );
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {

        if (Gate::denies('api-write:pump')) {
            return $this->respondUnauthorized();
        }

        $pump = Pump::find($id);
        if (is_null($pump)) {
            return $this->respondNotFound('Pump not found');
        }

        $pump->delete();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('pumps'),
                'delay' => 2000
            ]
        ]);

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        if (Gate::denies('api-write:pump')) {
            return $this->respondUnauthorized();
        }

        $pump = Pump::create();
        $pump->name = $request->input('name');
        $pump->save();

        return $this->setStatusCode(200)->respondWithData(
            [
                'id'    =>  $pump->id
            ],
            [
                'redirect' => [
                    'uri'   => url('pumps/' . $pump->id . '/edit'),
                    'delay' => 100
                ]
            ]
        );

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {

        if (Gate::denies('api-write:pump')) {
            return $this->respondUnauthorized();
        }

        $pump = Pump::find($request->input('id'));
        if (is_null($pump)) {
            return $this->respondNotFound('Pump not found');
        }

        if ($request->has('controlunit') && strlen($request->input('controlunit')) > 0) {
            $controlunit = Controlunit::find($request->input('controlunit'));
            if (is_null($controlunit)) {
                return $this->setStatusCode(422)->respondWithError('Controlunit not found');
            }
            $controlunit_id = $controlunit->id;
        }
        else {
            $controlunit_id = null;
        }

        $pump->name = $request->input('name');
        $pump->controlunit_id = $controlunit_id;

        $pump->save();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('pumps'),
                'delay' => 1000
            ]
        ]);

    }

}
