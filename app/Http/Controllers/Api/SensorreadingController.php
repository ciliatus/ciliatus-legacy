<?php

namespace App\Http\Controllers\Api;

use App\Http\Transformers\SensorreadingTransformer;
use App\LogicalSensor;
use App\Sensorreading;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;


/**
 * Error Namespace 100 - 199
 *
 * Class SensorreadingController
 * @package App\Http\Controllers
 */
class SensorreadingController extends ApiController
{

    /**
     * SensorreadingController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->errorCodeNamespace = '29';
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if (Gate::denies('api-list')) {
            return $this->respondUnauthorized();
        }

        $sensorreadings = Sensorreading::query();
        $sensorreadings = $this->filter($request, $sensorreadings);

        return $this->respondTransformedAndPaginated(
            $request,
            $sensorreadings
        );

    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {

        if (Gate::denies('api-read')) {
            return $this->respondUnauthorized();
        }

        $sr = Sensorreading::query();
        $sr = $this->filter($request, $sr);
        $sr = $sr->find($id);

        if (!$sr) {
            return $this->respondNotFound();
        }

        return $this->setStatusCode(200)->respondWithData(
            (new SensorreadingTransformer())->transform(
                $sr->toArray()
            )
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        if(Gate::denies('api-write:sensorreading')) {
            return $this->respondUnauthorized();
        }

        $required_inputs = ['group_id', 'logical_sensor_id', 'rawvalue'];

        if (!$this->checkInput($required_inputs, $request)) {
            return $this->setStatusCode(422)
                        ->setErrorCode('104')
                        ->respondWithErrorDefaultMessage([
                            'missing_fields' => implode(',', $required_inputs)
                        ]);
        }

        if (!$this->checkUuid($request->input('group_id'))) {
            return $this->setStatusCode(422)
                        ->setErrorCode('106')
                        ->respondWithErrorDefaultMessage(['uuid' => 'group_id']);
        }

        if (!$this->checkUuid($request->input('logical_sensor_id'))) {
            return $this->setStatusCode(422)
                        ->setErrorCode('106')
                        ->respondWithErrorDefaultMessage(['uuid' => 'logical_sensor_id']);
        }

        $logical_sensor = LogicalSensor::find($request->input('logical_sensor_id'));
        if (is_null($logical_sensor)) {
            return $this->respondRelatedModelNotFound(LogicalSensor::class);
        }

        $existing_sensorreading = Sensorreading::where('sensorreadinggroup_id', $request->input('group_id'))->where('logical_sensor_id', $request->input('logical_sensor_id'))->first();
        if (!is_null($existing_sensorreading)) {
            return $this->setStatusCode(422)
                        ->setErrorCode('202')
                        ->respondWithErrorDefaultMessage();
        }

        if ($request->filled('read_at')) {
            try {
                Carbon::parse($request->input('read_at'));
            }
            catch (\Exception $ex) {
                return $this->setStatusCode(422)
                    ->setErrorCode('103')
                    ->respondWithErrorDefaultMessage($ex->getMessage());
            }

            $read_at = $request->input('read_at');
        }
        else {
            $read_at = Carbon::now();
        }

        $rawvalue = (float)$request->input('rawvalue');
        $adjusted_value = $rawvalue + $logical_sensor->getRawvalueAdjustment();

        if (!$logical_sensor->checkAdjustedValue($adjusted_value)) {
            return $this->setStatusCode(422)
                ->setErrorCode('201')
                ->respondWithErrorDefaultMessage();
        }

        $ls_most_recent_sr = $logical_sensor->sensorreadings()
                                            ->orderBy('read_at', 'desc')
                                            ->limit(1)
                                            ->get()
                                            ->first();

        /**
         * @var Sensorreading $sensorreading
         */
        $sensorreading = Sensorreading::create([
            'sensorreadinggroup_id' => $request->input('group_id'),
            'logical_sensor_id'     => $request->input('logical_sensor_id'),
            'rawvalue'              => $rawvalue,
            'adjusted_value'        => $adjusted_value,
            'rawvalue_adjustment'   => $logical_sensor->getRawvalueAdjustment(),
            'read_at'               => $read_at
        ]);

        if (is_null($ls_most_recent_sr) || $ls_most_recent_sr->read_at->lt($sensorreading->read_at)) {
            $logical_sensor->rawvalue = $rawvalue;
            $logical_sensor->adjusted_value = $adjusted_value;
            $logical_sensor->last_reading_at = Carbon::now();
            $logical_sensor->save();

            if (!is_null($logical_sensor->physical_sensor)) {
                if (!is_null($logical_sensor->physical_sensor->terrarium))
                    $logical_sensor->physical_sensor->terrarium->save();

                if (!is_null($logical_sensor->physical_sensor->room))
                    $logical_sensor->physical_sensor->room->save();

                if (!is_null($logical_sensor->physical_sensor->controlunit))
                    $logical_sensor->physical_sensor->controlunit->heartbeat();

                $logical_sensor->physical_sensor->heartbeat();
            }
        }

        return $this->setStatusCode(200)->respondWithData(
            [
                'id'    =>  $sensorreading->id
            ]
        );

    }

}
