<?php

namespace App\Http\Controllers\Api;

use App\Controlunit;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;

/**
 * Class ControlunitController
 * @package App\Http\Controllers
 */
class ControlunitController extends ApiController
{

    /**
     * ControlunitController constructor.
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->errorCodeNamespace = '1D';
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
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        return parent::default_show($request, $id);
    }


    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Request $request, $id)
    {

        if (Gate::denies('api-write:controlunit')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var Controlunit $controlunit
         */
        $controlunit = Controlunit::find($id);
        if (is_null($controlunit)) {
            return $this->respondNotFound();
        }

        $controlunit->delete();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('controlunits')
            ]
        ]);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        if (Gate::denies('api-write:controlunit')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var Controlunit $controlunit
         */
        $controlunit = Controlunit::create([
            'name' => $request->input('name')
        ]);

        return $this->setStatusCode(200)->respondWithData(
            [
                'id'    =>  $controlunit->id
            ],
            [
                'redirect' => [
                    'uri'   => url('controlunits/' . $controlunit->id . '/edit')
                ]
            ]
        );

    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {

        if (Gate::denies('api-write:controlunit')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var Controlunit $controlunit
         */
        $controlunit = Controlunit::find($id);
        if (is_null($controlunit)) {
            return $this->respondNotFound();
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
                'uri'   => url('controlunits')
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

        /**
         * @var Controlunit $controlunit
         */
        $controlunit = Controlunit::find($id);
        if (is_null($controlunit)) {
            return $this->respondNotFound();
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

        /**
         * @var Controlunit $controlunit
         */
        $controlunit = Controlunit::find($id);
        if (is_null($controlunit)) {
            return $this->respondNotFound();
        }

        if ($request->filled('software_version')) {
            $controlunit->software_version = $request->input('software_version');
        }

        if ($request->filled('client_time')) {
            try {
                $client_time = Carbon::parse($request->input('client_time'));
            }
            catch (\Exception $ex) {
                return $this->setStatusCode(422)
                            ->setErrorCode('103')
                            ->respondWithErrorDefaultMessage(['timestamp' => 'client_time']);
            }

            $controlunit->client_server_time_diff_seconds = Carbon::now()->diffInSeconds($client_time);
        }

        $controlunit->save();

        return $this->respondWithData([
            'id' => $controlunit->id,
            'server_time' => Carbon::now()
        ]);
    }

}
