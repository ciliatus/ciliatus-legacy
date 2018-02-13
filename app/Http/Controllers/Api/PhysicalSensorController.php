<?php

namespace App\Http\Controllers\Api;

use App\Controlunit;
use App\LogicalSensor;
use App\PhysicalSensor;
use Gate;
use Illuminate\Http\Request;


/**
 * Class TerrariumController
 * @package App\Http\Controllers
 */
class PhysicalSensorController extends ApiController
{

    /**
     * PhysicalSensorController constructor.
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
     * @throws \ErrorException
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

        if (Gate::denies('api-write:physical_sensor')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var PhysicalSensor $physical_sensor
         */
        $physical_sensor = PhysicalSensor::with('controlunit', 'logical_sensors', 'terrarium')->find($id);
        if (is_null($physical_sensor)) {
            return $this->respondNotFound('PhysicalSensor not found');
        }

        $logical_sensors = LogicalSensor::where('physical_sensor_id', $physical_sensor->id)->get();
        foreach ($logical_sensors as $ls) {
            $ls->physical_sensor_id = null;
            $ls->save();
        }

        $physical_sensor->delete();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('physical_sensors')
            ]
        ]);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        if (Gate::denies('api-write:physical_sensor')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var PhysicalSensor $physical_sensor
         */
        $physical_sensor = PhysicalSensor::create([
            'name' => $request->input('name')
        ]);

        $this->update($request, $physical_sensor->id);

        return $this->setStatusCode(200)->respondWithData(
            [
                'id'    =>  $physical_sensor->id
            ],
            [
                'redirect' => [
                    'uri'   => url('physical_sensors/' . $physical_sensor->id . '/edit')
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

        if (Gate::denies('api-write:physical_sensor')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var PhysicalSensor $physical_sensor
         */
        $physical_sensor = PhysicalSensor::find($id);
        if (is_null($physical_sensor)) {
            return $this->respondNotFound('PhysicalSensor not found');
        }

        if ($request->filled('controlunit')) {
            $controlunit = Controlunit::find($request->input('controlunit'));
            if (is_null($controlunit)) {
                return $this->setStatusCode(422)->respondWithError('Controlunit not found');
            }
        }

        $this->updateModelProperties($physical_sensor, $request, [
            'name', 'model', 'controlunit_id' => 'controlunit'
        ]);

        $this->updateExternalProperties($physical_sensor, $request, [
            'ControlunitConnectivity' => [
                'bus_type', 'i2c_address', 'i2c_multiplexer_address', 'i2c_multiplexer_port', 'gpio_pin'
            ]
        ]);

        if ($request->filled('terrarium')) {
            $physical_sensor->belongsTo_type = 'terrarium';
        }

        if ($request->filled('active')) {
            if ($request->input('active') == 'on' && !$physical_sensor->active()) {
                $physical_sensor->enable();
            }
            else if ($request->input('active') == 'off' && $physical_sensor->active()) {
                $physical_sensor->disable();
            }
        }

        $physical_sensor = $this->addBelongsTo($request, $physical_sensor);

        $physical_sensor->save();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('physical_sensors')
            ]
        ]);

    }

}
