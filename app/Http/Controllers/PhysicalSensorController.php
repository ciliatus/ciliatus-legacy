<?php

namespace App\Http\Controllers;

use App\Http\Transformers\PhysicalSensorTransformer;
use App\PhysicalSensor;
use Gate;
use Illuminate\Http\Request;

use App\Http\Requests;


/**
 * Class TerrariumController
 * @package App\Http\Controllers
 */
class PhysicalSensorController extends ApiController
{
    /**
     * @var PhysicalSensorTransformer
     */
    protected $physicalSensorTransformer;


    /**
     * PhysicalSensorController constructor.
     * @param PhysicalSensorTransformer $_physicalSensorTransformer
     */
    public function __construct(PhysicalSensorTransformer $_physicalSensorTransformer)
    {
        parent::__construct();
        $this->physicalSensorTransformer = $_physicalSensorTransformer;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        if (Gate::denies('api-list')) {
            return $this->respondUnauthorized();
        }

        $physical_sensors = PhysicalSensor::paginate(10);

        return $this->setStatusCode(200)->respondWithPagination($this->physicalSensorTransformer->transformCollection($physical_sensors->toArray()['data']), $physical_sensors);
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

        $physical_sensor = PhysicalSensor::find($id)->with('logical_sensors')->get()->first();

        if (!$physical_sensor) {
            return $this->respondNotFound('Physical sensor not found');
        }

        return $this->setStatusCode(200)->respondWithData($this->physicalSensorTransformer->transform($physical_sensor->toArray()));
    }

}
