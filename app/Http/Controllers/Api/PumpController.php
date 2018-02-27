<?php

namespace App\Http\Controllers\Api;

use App\Controlunit;
use App\Pump;
use Gate;
use Illuminate\Http\Request;


/**
 * Class PumpController
 * @package App\Http\Controllers
 */
class PumpController extends ApiController
{

    /**
     * PumpController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->errorCodeNamespace = '28';
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

        if (Gate::denies('api-write:pump')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var Pump $pump
         */
        $pump = Pump::find($id);
        if (is_null($pump)) {
            return $this->respondNotFound();
        }

        $pump->delete();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('pumps')
            ]
        ]);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        if (Gate::denies('api-write:pump')) {
            return $this->respondUnauthorized();
        }

        if (!$request->has('name')) {
            return $this->setStatusCode(422)
                        ->setErrorCode('104')
                        ->respondWithErrorDefaultMessage(['missing_fields' => 'name']);
        }

        /**
         * @var Pump $pump
         */
        $pump = Pump::create([
            'name' => $request->input('name')
        ]);

        $this->update($request, $pump->id);

        return $this->setStatusCode(200)->respondWithData(
            [
                'id'    =>  $pump->id
            ],
            [
                'redirect' => [
                    'uri'   => url('pumps/' . $pump->id . '/edit')
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

        if (Gate::denies('api-write:pump')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var Pump $pump
         */
        $pump = Pump::find($id);
        if (is_null($pump)) {
            return $this->respondNotFound();
        }

        if ($request->filled('controlunit') && strlen($request->input('controlunit')) > 0) {
            $controlunit = Controlunit::find($request->input('controlunit'));
            if (is_null($controlunit)) {
                return $this->respondRelatedModelNotFound(Controlunit::class);
            }
        }

        $this->updateModelProperties($pump, $request, [
            'name', 'controlunit_id' => 'controlunit', 'model'
        ]);

        $this->updateExternalProperties($pump, $request, [
            'ControlunitConnectivity' => [
                'bus_type', 'i2c_address', 'i2c_multiplexer_address', 'i2c_multiplexer_port', 'gpio_pin'
            ]
        ]);
        $pump->save();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('pumps')
            ]
        ]);

    }

}
