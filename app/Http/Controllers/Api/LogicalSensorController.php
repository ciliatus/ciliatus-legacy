<?php

namespace App\Http\Controllers\Api;

use App\LogicalSensor;
use App\LogicalSensorThreshold;
use App\PhysicalSensor;
use Gate;
use Illuminate\Http\Request;


/**
 * Class TerrariumController
 * @package App\Http\Controllers
 */
class LogicalSensorController extends ApiController
{

    /**
     * LogicalSensorController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->errorCodeNamespace = '24';
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

        if (Gate::denies('api-write:logical_sensor')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var LogicalSensor $logical_sensor
         */
        $logical_sensor = LogicalSensor::find($id);
        if (is_null($logical_sensor)) {
            return $this->respondNotFound();
        }

        $ths = LogicalSensorThreshold::where('logical_sensor_id', $logical_sensor->id)->get();
        foreach ($ths as $th) {
            $th->delete();
        }

        $logical_sensor->delete();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('logical_sensors')
            ]
        ]);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        if (Gate::denies('api-write:logical_sensor')) {
            return $this->respondUnauthorized();
        }

        $physical_sensor = PhysicalSensor::find($request->input('physical_sensor'));
        if (is_null($physical_sensor)) {
            return $this->respondRelatedModelNotFound(PhysicalSensor::class);
        }

        /**
         * @var LogicalSensor $logical_sensor
         */
        $logical_sensor = LogicalSensor::create();
        $logical_sensor->name = $request->input('name');
        $logical_sensor->physical_sensor_id = $physical_sensor->id;

        $logical_sensor->save();

        $this->update($request, $logical_sensor->id);

        return $this->setStatusCode(200)->respondWithData(
            [
                'id'    =>  $logical_sensor->id
            ],
            [
                'redirect' => [
                    'uri'   => url('logical_sensors/' . $logical_sensor->id . '/edit')
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

        if (Gate::denies('api-write:logical_sensor')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var LogicalSensor $logical_sensor
         */
        $logical_sensor = LogicalSensor::find($id);
        if (is_null($logical_sensor)) {
            return $this->respondNotFound();
        }

        if ($request->filled('physical_sensor')) {
            $physical_sensor = PhysicalSensor::find($request->input('physical_sensor'));
            if (is_null($physical_sensor)) {
                return $this->respondRelatedModelNotFound(PhysicalSensor::class);
            }
        }

        $this->updateModelProperties($logical_sensor, $request, [
            'physical_sensor_id' => 'physical_sensor', 'name', 'type',
            'rawvalue_lowerlimit', 'rawvalue_upperlimit'
        ]);

        $this->updateExternalProperties($logical_sensor, $request, [
            'LogicalSensorAccuracy' => [
                'adjust_rawvalue'
            ]
        ]);

        $logical_sensor->save();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('logical_sensors')
            ]
        ]);

    }

}
