<?php

namespace App\Http\Controllers\Api;

use App\Controlunit;
use App\Http\Transformers\LogicalSensorThresholdTransformer;
use App\LogicalSensor;
use App\LogicalSensorThreshold;
use App\PhysicalSensor;
use Cache;
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
     * @var LogicalSensorThresholdTransformer
     */
    protected $logicalSensorTransformer;

    /**
     * @var array
     */
    protected $casts = [
        'active'    =>  'boolean'
    ];

    /**
     * LogicalSensorThresholdController constructor.
     * @param LogicalSensorThresholdTransformer $_logicalSensorTransformer
     */
    public function __construct(LogicalSensorThresholdTransformer $_logicalSensorTransformer)
    {
        parent::__construct();
        $this->logicalSensorTransformer = $_logicalSensorTransformer;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        if (Gate::denies('api-list')) {
            return $this->respondUnauthorized();
        }

        $logical_sensor_thresholds = LogicalSensorThreshold::paginate(10);

        return $this->setStatusCode(200)->respondWithPagination(
            $this->logicalSensorTransformer->transformCollection(
                $logical_sensor_thresholds->toArray()['data']
            ),
            $logical_sensor_thresholds
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

        $logical_sensor_threshold = LogicalSensorThreshold::with('logical_sensor_threshold')->find($id);

        if (!$logical_sensor_threshold) {
            return $this->respondNotFound('LogicalSensorThreshold not found');
        }

        return $this->setStatusCode(200)->respondWithData(
            $this->logicalSensorTransformer->transform(
                $logical_sensor_threshold->toArray()
            )
        );
    }


    /**
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

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('logical_sensor_thresholds'),
                'delay' => 2000
            ]
        ]);

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        if (Gate::denies('api-write:logical_sensor_threshold')) {
            return $this->respondUnauthorized();
        }

        $logical_sensor_threshold = LogicalSensorThreshold::create();
        $logical_sensor_threshold->logical_sensor_id = $request->input('logical_sensor');
        $logical_sensor_threshold->save();

        return $this->setStatusCode(200)->respondWithData(
            [
                'id'    =>  $logical_sensor_threshold->id
            ],
            [
                'redirect' => [
                    'uri'   => url('logical_sensor_thresholds/' . $logical_sensor_threshold->id . '/edit'),
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

        if (Gate::denies('api-write:logical_sensor_threshold')) {
            return $this->respondUnauthorized();
        }

        $logical_sensor_threshold = LogicalSensorThreshold::find($request->input('sensor_id'));
        if (is_null($logical_sensor_threshold)) {
            return $this->respondNotFound('LogicalSensorThreshold not found');
        }

        if ($request->has('logical_sensor') && strlen($request->input('logical_sensor')) > 0) {
            $logical_sensor = LogicalSensor::find($request->input('logical_sensor'));
            if (is_null($logical_sensor)) {
                return $this->setStatusCode(422)->respondWithError('LogicalSensor not found');
            }
            $logical_sensor_id = $logical_sensor->id;
        }
        else {
            $logical_sensor_id = null;
        }

        $logical_sensor_threshold->starts_at = Carbon::parse($request->input('starts_at'));
        $logical_sensor_threshold->rawvalue_lowerlimit = strlen($request->input('sensor_lowerlimit')) < 1 ? null : $request->input('sensor_lowerlimit');
        $logical_sensor_threshold->rawvalue_upperlimit = strlen($request->input('sensor_upperlimit')) < 1 ? null: $request->input('sensor_upperlimit');
        $logical_sensor_threshold->logical_sensor_id = $logical_sensor_id;
        $logical_sensor_threshold->active = $request->has('active');

        $logical_sensor_threshold->save();

        if (is_null($logical_sensor_threshold->logical_sensor)) {
            $redirect_uri = url('logical_sensor_thresholds/' . $logical_sensor_threshold->id);
        }
        else {
            $redirect_uri = url('logical_sensors/' . $logical_sensor_threshold->logical_sensor->id . '/edit');
        }

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => $redirect_uri,
                'delay' => 1000
            ]
        ]);

    }

    public function copy(Request $request)
    {
        if (Gate::denies('api-write:logical_sensor_threshold')) {
            return $this->respondUnauthorized();
        }

        $logical_sensor_source = LogicalSensor::find($request->input('logical_sensor_source'));
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

