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
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->errorCodeNamespace = '25';
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

        if (Gate::denies('api-write:logical_sensor_threshold')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var LogicalSensorThreshold $logical_sensor_threshold
         */
        $logical_sensor_threshold = LogicalSensorThreshold::find($id);
        if (is_null($logical_sensor_threshold)) {
            return $this->respondNotFound();
        }

        $logical_sensor_threshold->delete();

        return $this->setStatusCode(200)->respondWithData([],
            [
                'redirect' => [
                    'uri'   => url('logical_sensors/' . $logical_sensor_threshold->logical_sensor_id . '/edit')
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
                return $this->respondRelatedModelNotFound(LogicalSensor::class);
            }
        }

        /**
         * @var LogicalSensorThreshold $logical_sensor_threshold
         */
        $logical_sensor_threshold = new LogicalSensorThreshold();
        $logical_sensor_threshold->logical_sensor_id = is_null($ls) ? null : $ls->id;
        $logical_sensor_threshold = $this->addBelongsTo($request, $logical_sensor_threshold, false);
        $logical_sensor_threshold->starts_at = Carbon::parse($request->input('starts_at'));
        $logical_sensor_threshold->adjusted_value_lowerlimit = strlen($request->input('lowerlimit')) < 1 ? null : $request->input('lowerlimit');
        $logical_sensor_threshold->adjusted_value_upperlimit = strlen($request->input('upperlimit')) < 1 ? null: $request->input('upperlimit');
        $logical_sensor_threshold->active = true;
        $logical_sensor_threshold->save();

        return $this->setStatusCode(200)->respondWithData(
            [
                'id'    =>  $logical_sensor_threshold->id
            ],
            [
                'redirect' => [
                    'uri'   => url('logical_sensors/' . $logical_sensor_threshold->logical_sensor_id . '/edit')
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
            return $this->respondNotFound();
        }

        if ($request->filled('logical_sensor')) {
            $logical_sensor = LogicalSensor::find($request->input('logical_sensor'));
            if (is_null($logical_sensor)) {
                return $this->respondRelatedModelNotFound(LogicalSensor::class);
            }
        }

        $this->updateModelProperties($logical_sensor_threshold, $request, [
            'logical_sensor_id' => 'logical_sensor',
            'adjusted_value_lowerlimit' => 'lowerlimit',
            'adjusted_value_upperlimit' => 'upperlimit'
        ]);
        $logical_sensor_threshold = $this->addBelongsTo($request, $logical_sensor_threshold, false);
        $logical_sensor_threshold->starts_at = Carbon::parse($request->input('starts_at'));
        $logical_sensor_threshold->active = true;
        $logical_sensor_threshold->save();

        return $this->setStatusCode(200)->respondWithData([],
            [
                'redirect' => [
                    'uri'   => url('logical_sensors/' . $logical_sensor_threshold->logical_sensor_id . '/edit')
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
            return $this->respondNotFound();
        }

        $logical_sensor_target = LogicalSensor::find($request->input('logical_sensor_target'));
        if (is_null($logical_sensor_target)) {
            return $this->respondNotFound();
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
                'uri'   => url('logical_sensors/' . $logical_sensor_target->id . '/edit')
            ]
        ]);
    }
}

