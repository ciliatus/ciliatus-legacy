<?php

namespace App\Http\Controllers\Api;

use App\Controlunit;
use App\Pump;
use App\Http\Transformers\ValveTransformer;
use App\Valve;
use App\Terrarium;
use Cache;
use Gate;
use Illuminate\Http\Request;


/**
 * Class ValveController
 * @package App\Http\Controllers
 */
class ValveController extends ApiController
{
    /**
     * @var ValveTransformer
     */
    protected $valveTransformer;

    /**
     * ValveController constructor.
     * @param ValveTransformer $_valveTransformer
     */
    public function __construct(ValveTransformer $_valveTransformer)
    {
        parent::__construct();
        $this->valveTransformer = $_valveTransformer;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        if (Gate::denies('api-list')) {
            return $this->respondUnauthorized();
        }

        $valves = Valve::paginate(10);

        return $this->setStatusCode(200)->respondWithPagination(
            $this->valveTransformer->transformCollection(
                $valves->toArray()['data']
            ),
            $valves
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

        $valve = Valve::find($id);

        if (!$valve) {
            return $this->respondNotFound('Valve not found');
        }
        return $this->setStatusCode(200)->respondWithData(
            $this->valveTransformer->transform(
                $valve->toArray()
            )
        );
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {

        if (Gate::denies('api-write:valve')) {
            return $this->respondUnauthorized();
        }

        $valve = Valve::find($id);
        if (is_null($valve)) {
            return $this->respondNotFound('Valve not found');
        }

        $valve->delete();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('valves'),
                'delay' => 2000
            ]
        ]);

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        if (Gate::denies('api-write:valve')) {
            return $this->respondUnauthorized();
        }

        $valve = Valve::create();
        $valve->name = $request->input('name');
        $valve->save();

        return $this->setStatusCode(200)->respondWithData(
            [
                'id'    => $valve->id
            ],
            [
                'redirect' => [
                    'uri'   => url('valves/' . $valve->id . '/edit'),
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

        if (Gate::denies('api-write:valve')) {
            return $this->respondUnauthorized();
        }

        $valve = Valve::find($request->input('id'));
        if (is_null($valve)) {
            return $this->respondNotFound('Valve not found');
        }

        if ($request->has('pump') && strlen($request->input('pump')) > 0) {
            $pump = Pump::find($request->input('pump'));
            if (is_null($pump)) {
                return $this->setStatusCode(422)->respondWithError('Pump not found');
            }
            $pump_id = $pump->id;
        }
        else {
            $pump_id = null;
        }

        if ($request->has('terrarium') && strlen($request->input('terrarium')) > 0) {
            $terrarium = Terrarium::find($request->input('terrarium'));
            if (is_null($terrarium)) {
                return $this->setStatusCode(422)->respondWithError('Terrarium not found');
            }
            $terrarium_id = $terrarium->id;
        }
        else {
            $terrarium_id = null;
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

        $valve->name = $request->input('name');
        $valve->pump_id = $pump_id;
        $valve->terrarium_id = $terrarium_id;
        $valve->controlunit_id = $controlunit_id;

        $valve->save();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('valves'),
                'delay' => 1000
            ]
        ]);

    }

}
