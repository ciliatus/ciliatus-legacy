<?php

namespace App\Http\Controllers;

use App\LogicalSensor;
use App\Sensorreading;
use App\Http\Transformers\SensorreadingTransformer;
use Gate;
use Request;


/**
 * Error Namespace 100 - 199
 *
 * Class SensorreadingController
 * @package App\Http\Controllers
 */
class SensorreadingController extends ApiController
{
    /**
     * @var
     */
    protected $sensorreadingTransformer;

    /**
     * SensorreadingController constructor.
     * @param SensorreadingTransformer $_sensorreading
     */
    public function __construct(SensorreadingTransformer $_sensorreadingTransformer)
    {
        parent::__construct();
        $this->sensorreadingTransformer = $_sensorreadingTransformer;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        if (Gate::denies('api-list')) {
            return $this->respondUnauthorized();
        }

        $sensorreading = Sensorreading::paginate(100);

        return $this->setStatusCode(200)
                    ->respondWithPagination($this->sensorreadingTransformer->transformCollection($sensorreading->toArray()['data']), $sensorreading);
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

        $sensorreading = Sensorreading::find($id);

        if (!$sensorreading) {
            return $this->respondNotFound('Sensorreading not found');
        }

        return $this->setStatusCode(200)
                    ->respondWithData($this->sensorreadingTransformer->transform($sensorreading->toArray()));
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {

        if(Gate::denies('api-write:sensorreading')) {
            return $this->respondUnauthorized();
        }

        $required_inputs = ['group_id', 'logical_sensor_id', 'rawvalue'];

        if (!$this->checkInput($required_inputs)) {
            return $this->setStatusCode(422)
                        ->setErrorCode(101)
                        ->respondWithError('Required inputs: ' . implode(',', $required_inputs));
        }

        $data = Request::all();

        if (!$this->checkUuid($data['group_id'])) {
            return $this->setStatusCode(422)
                        ->setErrorCode(102)
                        ->respondWithError('group_id must be a valid uuid');
        }

        if (!$this->checkUuid($data['logical_sensor_id'])) {
            return $this->setStatusCode(422)
                        ->setErrorCode(103)
                        ->respondWithError('logical_sensor_id must be a valid uuid');
        }

        $logical_sensor = LogicalSensor::find($data['logical_sensor_id']);
        if (is_null($logical_sensor)) {
            return $this->setStatusCode(422)
                        ->setErrorCode(104)
                        ->respondWithError('LogicalSensor not found');
        }

        if (!$logical_sensor->checkRawValue($data['rawvalue'])) {
            return $this->setStatusCode(422)
                        ->setErrorCode(105)
                        ->respondWithError('rawvalue out of range');
        }

        $existing_sensorreading = Sensorreading::where('sensorreadinggroup_id', $data['group_id'])->where('logical_sensor_id', $data['logical_sensor_id'])->first();
        if (!is_null($existing_sensorreading)) {
            return $this->setStatusCode(422)
                        ->setErrorCode(106)
                        ->respondWithError('The reading group already has a reading for this logical sensor');
        }

        $sensorreading = Sensorreading::create();
        $sensorreading->sensorreadinggroup_id = $data['group_id'];
        $sensorreading->logical_sensor_id = $data['logical_sensor_id'];
        $sensorreading->rawvalue = $data['rawvalue'];
        $sensorreading->save();

        return $this->setStatusCode(200)->respondWithData($this->sensorreadingTransformer->transform($sensorreading->toArray()));

    }

}
