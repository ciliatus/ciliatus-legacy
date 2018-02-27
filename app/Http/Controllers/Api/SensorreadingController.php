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
                        ->setErrorCode('10x001')
                        ->respondWithErrorDefaultMessage(implode(',', $required_inputs));
        }

        if (!$this->checkUuid($request->input('group_id'))) {
            return $this->setStatusCode(422)
                        ->setErrorCode('10x002')
                        ->respondWithErrorDefaultMessage();
        }

        if (!$this->checkUuid($request->input('logical_sensor_id'))) {
            return $this->setStatusCode(422)
                        ->setErrorCode('10x003')
                        ->respondWithErrorDefaultMessage();
        }

        $logical_sensor = LogicalSensor::find($request->input('logical_sensor_id'));
        if (is_null($logical_sensor)) {
            return $this->setStatusCode(422)
                        ->setErrorCode('10x004')
                        ->respondWithErrorDefaultMessage();
        }

        if (!$logical_sensor->checkRawValue((float)$request->input('rawvalue'))) {
            return $this->setStatusCode(422)
                        ->setErrorCode('10x005')
                        ->respondWithErrorDefaultMessage();
        }

        $existing_sensorreading = Sensorreading::where('sensorreadinggroup_id', $request->input('group_id'))->where('logical_sensor_id', $request->input('logical_sensor_id'))->first();
        if (!is_null($existing_sensorreading)) {
            return $this->setStatusCode(422)
                        ->setErrorCode('10x006')
                        ->respondWithErrorDefaultMessage();
        }

        $logical_sensor->rawvalue = (float)$request->input('rawvalue');
        $logical_sensor->save();

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
                            ->setErrorCode('10x007')
                            ->respondWithErrorDefaultMessage($ex->getMessage());
            }

            $read_at = $request->input('read_at');
        }
        else {
            $read_at = Carbon::now();
        }

        /**
         * @var Sensorreading $sensorreading
         */
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
