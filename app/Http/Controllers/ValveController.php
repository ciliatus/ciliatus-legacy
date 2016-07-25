<?php

namespace App\Http\Controllers;

use App\Controlunit;
use App\Pump;
use App\Http\Transformers\ValveTransformer;
use App\Valve;
use App\Terrarium;
use Cache;
use Gate;
use Request;


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

        return $this->setStatusCode(200)->respondWithPagination($this->valveTransformer->transformCollection($valves->toArray()['data']), $valves);
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

        if (Cache::has('api-show-valve-' . $id)) {
            return $this->setStatusCode(200)->respondWithData($this->valveTransformer->transform(Cache::get('api-show-valve-' . $id)->toArray()));
        }

        $valve = Valve::with('physical_sensors', 'valves')->find($id);

        if (!$valve) {
            return $this->respondNotFound('Valve not found');
        }

        Cache::add('api-show-valve-' . $id, $valve, env('CACHE_API_TERRARIUM_SHOW_DURATION') / 60);

        return $this->setStatusCode(200)->respondWithData($this->valveTransformer->transform($valve->toArray()));
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy()
    {

        if (Gate::denies('api-write:valve')) {
            return $this->respondUnauthorized();
        }

        $data = Request::all();

        $valve = Valve::find($data['f_delete_valves_id']);
        if (is_null($valve)) {
            return $this->setStatusCode(422)->respondWithError('Valve not found');
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
    public function store()
    {

        if (Gate::denies('api-write:valve')) {
            return $this->respondUnauthorized();
        }

        $data = Request::all();

        $valve = Valve::create();
        $valve->name = $data['f_create_valve_name'];
        $valve->save();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('valves/' . $valve->id . '/edit'),
                'delay' => 100
            ]
        ]);

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function update()
    {

        if (Gate::denies('api-write:valve')) {
            return $this->respondUnauthorized();
        }

        $data = Request::all();

        $valve = Valve::find($data['f_edit_valve_id']);
        if (is_null($valve)) {
            return $this->setStatusCode(422)->respondWithError('Valve not found');
        }

        if (isset($data['f_edit_valve_pump']) && strlen($data['f_edit_valve_pump']) > 0) {
            $pump = Pump::find($data['f_edit_valve_pump']);
            if (is_null($pump)) {
                return $this->setStatusCode(422)->respondWithError('Pump not found');
            }
            $pump_id = $pump->id;
        }
        else {
            $pump_id = null;
        }

        if (isset($data['f_edit_valve_terrarium']) && strlen($data['f_edit_valve_terrarium']) > 0) {
            $terrarium = Terrarium::find($data['f_edit_valve_terrarium']);
            if (is_null($terrarium)) {
                return $this->setStatusCode(422)->respondWithError('Terrarium not found');
            }
            $terrarium_id = $terrarium->id;
        }
        else {
            $terrarium_id = null;
        }

        if (isset($data['f_edit_valve_controlunit']) && strlen($data['f_edit_valve_controlunit']) > 0) {
            $controlunit = Controlunit::find($data['f_edit_valve_controlunit']);
            if (is_null($controlunit)) {
                return $this->setStatusCode(422)->respondWithError('Controlunit not found');
            }
            $controlunit_id = $controlunit->id;
        }
        else {
            $controlunit_id = null;
        }

        $valve->name = $data['f_edit_valve_name'];
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
