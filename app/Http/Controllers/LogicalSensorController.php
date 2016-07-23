<?php

namespace App\Http\Controllers;

use App\Http\Transformers\LogicalSensorTransformer;
use App\LogicalSensor;
use Gate;
use Illuminate\Http\Request;

use App\Http\Requests;


/**
 * Class TerrariumController
 * @package App\Http\Controllers
 */
class LogicalSensorController extends ApiController
{
    /**
     * @var LogicalSensorTransformer
     */
    protected $logicalSensorTransformer;


    /**
     * LogicalSensorController constructor.
     * @param LogicalSensorTransformer $_logicalSensorTransformer
     */
    public function __construct(LogicalSensorTransformer $_logicalSensorTransformer)
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

        $logical_sensors = LogicalSensor::paginate(10);

        return $this->setStatusCode(200)->respondWithPagination($this->logicalSensorTransformer->transformCollection($logical_sensors->toArray()['data']), $logical_sensors);
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

        $logical_sensor = LogicalSensor::find($id);

        if (!$logical_sensor) {
            return $this->respondNotFound('Logical sensor not found');
        }

        return $this->setStatusCode(200)->respondWithData($this->logicalSensorTransformer->transform($logical_sensor->toArray()));
    }

}
