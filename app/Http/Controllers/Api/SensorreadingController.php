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
            return $this->respondNotFound('Sensorreading not found');
        }

        return $this->setStatusCode(200)->respondWithData(
            (new SensorreadingTransformer())->transform(
                $sr->toArray()
            )
        );
    }

    /**
     * Error codes:
     *  - 101: Missing input fields
     *  - 102: group_id is not a valid uuid
     *  - 103: logical_sensor_id is not a valid uuid
     *  - 104: LogicalSensor not found
     *  - 105: rawvalue out of range
     *  - 106: The reading group already has a reading for this logical sensor
     *
     *
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
                        ->setErrorCode(101)
                        ->respondWithError('Required inputs: ' . implode(',', $required_inputs));
        }

        if (!$this->checkUuid($request->input('group_id'))) {
            return $this->setStatusCode(422)
                        ->setErrorCode(102)
                        ->respondWithError('group_id must be a valid uuid');
        }

        if (!$this->checkUuid($request->input('logical_sensor_id'))) {
            return $this->setStatusCode(422)
                        ->setErrorCode(103)
                        ->respondWithError('logical_sensor_id must be a valid uuid');
        }

        $logical_sensor = LogicalSensor::find($request->input('logical_sensor_id'));
        if (is_null($logical_sensor)) {
            return $this->setStatusCode(422)
                        ->setErrorCode(104)
                        ->respondWithError('LogicalSensor not found');
        }

        if (!$logical_sensor->checkRawValue((float)$request->input('rawvalue'))) {
            return $this->setStatusCode(422)
                        ->setErrorCode(105)
                        ->respondWithError('rawvalue out of range');
        }

        $existing_sensorreading = Sensorreading::where('sensorreadinggroup_id', $request->input('group_id'))->where('logical_sensor_id', $request->input('logical_sensor_id'))->first();
        if (!is_null($existing_sensorreading)) {
            return $this->setStatusCode(422)
                        ->setErrorCode(106)
                        ->respondWithError('The reading group already has a reading for this logical sensor');
        }

        $logical_sensor->rawvalue = (float)$request->input('rawvalue');
        $logical_sensor->save(['silent']);

        if (!is_null($logical_sensor->physical_sensor)) {

            if (!is_null($logical_sensor->physical_sensor->terrarium))
                $logical_sensor->physical_sensor->terrarium->save();

            if (!is_null($logical_sensor->physical_sensor->controlunit))
                $logical_sensor->physical_sensor->controlunit->heartbeat();

            $logical_sensor->physical_sensor->heartbeat();
        }

        if ($request->filled('read_at')) {
            try {
                Carbon::parse($request->input('read_at'));
            }
            catch (\Exception $ex) {
                return $this->setStatusCode(422)
                            ->setErrorCode(107)
                            ->respondWithError('Field created_at could not be parsed: ' . $ex->getMessage());
            }

            $read_at = $request->input('read_at');
        }
        else {
            $read_at = Carbon::now();
        }


        $sensorreading = Sensorreading::create([
            'sensorreadinggroup_id' => $request->input('group_id'),
            'logical_sensor_id'     => $request->input('logical_sensor_id'),
            'rawvalue'              => $logical_sensor->rawvalue,
            'read_at'               => $read_at
        ]);

        return $this->setStatusCode(200)->respondWithData(
            [
                'id'    =>  $sensorreading->id
            ]
        );

    }

}
