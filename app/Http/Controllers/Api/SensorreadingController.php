<?php

namespace App\Http\Controllers\Api;

use App\LogicalSensor;
use App\Sensorreading;
use App\Http\Transformers\SensorreadingTransformer;
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
     * @var
     */
    protected $sensorreadingTransformer;

    /**
     * SensorreadingController constructor.
     * @param SensorreadingTransformer $_sensorreadingTransformer
     */
    public function __construct(SensorreadingTransformer $_sensorreadingTransformer)
    {
        parent::__construct();
        $this->sensorreadingTransformer = $_sensorreadingTransformer;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if (Gate::denies('api-list')) {
            return $this->respondUnauthorized();
        }

        $sensorreadings = Sensorreading::with('logical_sensor');

        $sensorreadings = $this->filter($request, $sensorreadings);

        /*
         * If raw is passed, pagination will be ignored
         * Permission api-list:raw is required
         */
        if ($request->has('raw') && Gate::allows('api-list:raw')) {

            return $this->setStatusCode(200)->respondWithData(
                $this->sensorreadingTransformer->transformCollection(
                    $sensorreadings->get()->toArray()
                )
            );
        }

        $sensorreadings = $sensorreadings->paginate(env('PAGINATION_PER_PAGE', 100));

        return $this->setStatusCode(200)->respondWithPagination(
            $this->sensorreadingTransformer->transformCollection(
                $sensorreadings->toArray()['data']
            ),
            $sensorreadings
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

        $sensorreading = Sensorreading::find($id);

        if (!$sensorreading) {
            return $this->respondNotFound('Sensorreading not found');
        }

        return $this->setStatusCode(200)->respondWithData(
            $this->sensorreadingTransformer->transform(
                $sensorreading->toArray()
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

        if (!is_null($logical_sensor->physical_sensor)) {

            if (!is_null($logical_sensor->physical_sensor->terrarium))
                $logical_sensor->physical_sensor->terrarium->save();

            if (!is_null($logical_sensor->physical_sensor->controlunit))
                $logical_sensor->physical_sensor->controlunit->heartbeat();

            $logical_sensor->physical_sensor->heartbeat();
        }

        $logical_sensor->rawvalue = (float)$request->input('rawvalue');
        $logical_sensor->save(['silent']);

        $sensorreading = Sensorreading::create();
        $sensorreading->sensorreadinggroup_id = $request->input('group_id');
        $sensorreading->logical_sensor_id = $request->input('logical_sensor_id');
        $sensorreading->rawvalue = (float)$request->input('rawvalue');
        $sensorreading->save();

        return $this->setStatusCode(200)->respondWithData(
            [
                'id'    =>  $sensorreading->id
            ]
        );

    }

}
