<?php

namespace App\Http\Controllers\Api;

use App\LogicalSensor;
use App\LogicalSensorThreshold;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;


/**
 * Class TerrariumController
 * @package App\Http\Controllers
 */
class LogicalSensorThresholdController extends ApiController
{

    /**
     * LogicalSensorThresholdController constructor.
     */
    public function __construct()
    {
        parent::__construct();
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
     */
    public function destroy(Request $request, $id)
    {

        if (Gate::denies('api-write:logical_sensor_threshold')) {
            return $this->respondUnauthorized();
        }

        $logical_sensor_threshold = LogicalSensorThreshold::find($id);
        if (is_null($logical_sensor_threshold)) {
            return $this->respondNotFound('LogicalSensorThreshold not found');
        }

        $logical_sensor_threshold->delete();

        return $this->setStatusCode(200)->respondWithData([],
            [
                'redirect' => [
                    'uri'   => url('logical_sensors/' . $logical_sensor_threshold->logical_sensor_id . '/edit'),
                    'delay' => 100
                ]
            ]
        );

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        if (Gate::denies('api-write:logical_sensor_threshold')) {
            return $this->respondUnauthorized();
        }

        $ls = null;
        if ($request->filled('logical_sensor')) {
            $ls = LogicalSensor::find($request->input('logical_sensor'));
            if (is_null($ls)) {
                return $this->setStatusCode(422)->respondWithError('Logical sensor not found');
            }
        }

        $logical_sensor_threshold = LogicalSensorThreshold::create();
        $logical_sensor_threshold->logical_sensor_id = is_null($ls) ? null : $ls->id;
        $logical_sensor_threshold = $this->addBelongsTo($request, $logical_sensor_threshold, false);
        $logical_sensor_threshold->starts_at = Carbon::parse($request->input('starts_at'));
        $logical_sensor_threshold->rawvalue_lowerlimit = strlen($request->input('lowerlimit')) < 1 ? null : $request->input('lowerlimit');
        $logical_sensor_threshold->rawvalue_upperlimit = strlen($request->input('upperlimit')) < 1 ? null: $request->input('upperlimit');
        $logical_sensor_threshold->active = true;
        $logical_sensor_threshold->save();

        return $this->setStatusCode(200)->respondWithData(
            [
                'id'    =>  $logical_sensor_threshold->id
            ],
            [
                'redirect' => [
                    'uri'   => url('logical_sensors/' . $logical_sensor_threshold->logical_sensor_id . '/edit'),
                    'delay' => 100
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

        if (Gate::denies('api-write:logical_sensor_threshold')) {
            return $this->respondUnauthorized();
        }

        $logical_sensor_threshold = LogicalSensorThreshold::find($id);
        if (is_null($logical_sensor_threshold)) {
            return $this->respondNotFound('LogicalSensorThreshold not found');
        }

        if ($request->filled('logical_sensor')) {
            $logical_sensor = LogicalSensor::find($request->input('logical_sensor'));
            if (is_null($logical_sensor)) {
                return $this->setStatusCode(422)->respondWithError('LogicalSensor not found');
            }
        }

        $this->updateModelProperties($logical_sensor_threshold, $request, [
            'logical_sensor_id' => 'logical_sensor',
            'rawvalue_lowerlimit' => 'lowerlimit',
            'rawvalue_upperlimit' => 'upperlimit'
        ]);
        $logical_sensor_threshold = $this->addBelongsTo($request, $logical_sensor_threshold, false);
        $logical_sensor_threshold->starts_at = Carbon::parse($request->input('starts_at'));
        $logical_sensor_threshold->active = true;
        $logical_sensor_threshold->save();

        return $this->setStatusCode(200)->respondWithData([],
            [
                'redirect' => [
                    'uri'   => url('logical_sensors/' . $logical_sensor_threshold->logical_sensor_id . '/edit'),
                    'delay' => 100
                ]
            ]
        );

    }

    /**
     * @param Request $request
     * @param $source_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function copy(Request $request, $source_id)
    {
        if (Gate::denies('api-write:logical_sensor_threshold')) {
            return $this->respondUnauthorized();
        }

        $logical_sensor_source = LogicalSensor::find($source_id);
        if (is_null($logical_sensor_source)) {
            return $this->respondNotFound('Source LogicalSensor not found');
        }

        $logical_sensor_target = LogicalSensor::find($request->input('logical_sensor_target'));
        if (is_null($logical_sensor_target)) {
            return $this->respondNotFound('Target LogicalSensor not found');
        }

        foreach ($logical_sensor_target->thresholds as $t) {
            $t->delete();
        }

        foreach ($logical_sensor_source->thresholds as $t) {
            $new_t = $t->replicate();
            $new_t->logical_sensor_id = $logical_sensor_target->id;
            $new_t->save();
        }

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('logical_sensors/' . $logical_sensor_target->id . '/edit'),
                'delay' => 1000
            ]
        ]);
    }
}

