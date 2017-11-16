<?php

namespace App\Http\Controllers\Api;

use App\Controlunit;
use Gate;
use Illuminate\Http\Request;

/**
 * Class ControlunitController
 * @package App\Http\Controllers
 */
class ControlunitController extends ApiController
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {

        if (Gate::denies('api-write:controlunit')) {
            return $this->respondUnauthorized();
        }

        $controlunit = Controlunit::find($id);
        if (is_null($controlunit)) {
            return $this->setStatusCode(422)->respondWithError('Controlunit not found');
        }

        $controlunit->delete();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('controlunits'),
                'delay' => 2000
            ]
        ]);

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        if (Gate::denies('api-write:controlunit')) {
            return $this->respondUnauthorized();
        }

        $controlunit = Controlunit::create([
            'name' => $request->input('name')
        ]);

        return $this->setStatusCode(200)->respondWithData(
            [
                'id'    =>  $controlunit->id
            ],
            [
                'redirect' => [
                    'uri'   => url('controlunits/' . $controlunit->id . '/edit'),
                    'delay' => 100
                ]
            ]
        );

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {

        if (Gate::denies('api-write:controlunit')) {
            return $this->respondUnauthorized();
        }

        $controlunit = Controlunit::find($id);
        if (is_null($controlunit)) {
            return $this->setStatusCode(422)->respondWithError('Controlunit not found');
        }

        $this->updateModelProperties($controlunit, $request, [
            'name'
        ]);

        $this->updateExternalProperties($controlunit, $request, [
            'ControlunitConnectivity' => [
                'i2c_bus_num'
            ]
        ]);

        if ($request->filled('active')) {
            if ($request->input('active') == 'on' && !$controlunit->active()) {
                $controlunit->enable();
            }
            else if ($request->input('active') == 'off' && $controlunit->active()) {
                $controlunit->disable();
            }
        }

        $controlunit->save();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('controlunits'),
                'delay' => 1000
            ]
        ]);

    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchDesiredStates($id)
    {
        if (Gate::denies('api-fetch:desired_states')) {
            return $this->respondUnauthorized();
        }

        $controlunit = Controlunit::find($id);
        if (is_null($controlunit)) {
            return $this->setStatusCode(422)->respondWithError('Controlunit not found');
        }

        return $this->respondWithData($controlunit->fetchAndAckDesiredStates());
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function check_in(Request $request, $id)
    {
        if (Gate::denies('api-write:controlunit')) {
            return $this->respondUnauthorized();
        }

        $controlunit = Controlunit::find($id);
        if (is_null($controlunit)) {
            return $this->setStatusCode(422)->respondWithError('Controlunit not found');
        }

        if ($request->filled('software_version')) {
            $controlunit->software_version = $request->input('software_version');
        }

        $controlunit->save();

        return $this->respondWithData([]);
    }

}
