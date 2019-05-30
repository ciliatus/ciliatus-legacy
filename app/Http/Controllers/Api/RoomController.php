<?php

namespace App\Http\Controllers\Api;

use App\Animal;
use App\Http\Transformers\PhysicalSensorTransformer;
use App\Http\Transformers\RoomTransformer;
use App\Repositories\GenericRepository;
use App\Repositories\SensorreadingRepository;
use App\Repositories\RoomRepository;
use App\Sensorreading;
use App\Room;
use App\Valve;
use Gate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


/**
 * Class RoomController
 * @package App\Http\Controllers
 */
class RoomController extends ApiController
{

    /**
     * RoomController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->errorCodeNamespace = '2F';
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {

        if (Gate::denies('api-list')) {
            return $this->respondUnauthorized();
        }

        $rooms = Room::query();
        $rooms = $this->filter($request, $rooms);

        $repository_parameters = [
            'history_to'        => $request->filled('history_to') ?
                                    $request->input('history_to') :
                                    null,
            'history_minutes'   => $request->filled('history_minutes') ?
                                    $request->input('history_minutes') :
                                    env('ROOM_DEFAULT_HISTORY_MINUTES', 180)
        ];

        return $this->respondTransformedAndPaginated(
            $request,
            $rooms,
            $repository_parameters
        );

    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function show(Request $request, $id)
    {

        if (Gate::denies('api-read')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var Room $t
         */
        $room = Room::query();
        $room = $this->filter($request, $room);
        $room = $room->find($id);

        if (!$room) {
            return $this->respondNotFound();
        }

        $history_to = $request->filled('history_to') ? $request->input('history_to') : null;
        $history_minutes = $request->filled('history_minutes') ? $request->input('history_minutes') : env('ROOM_DEFAULT_HISTORY_MINUTES', 180);

        return $this->setStatusCode(200)
                    ->respondWithData(
                        (new RoomTransformer())
                             ->transform((new RoomRepository($room))
                                         ->show($history_to, $history_minutes)
                                         ->toArray())
                    );
    }


    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Request $request, $id)
    {

        if (Gate::denies('api-write:room')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var Room $room
         */
        $room = Room::find($id);
        if (is_null($room)) {
            return $this->respondNotFound();
        }

        /*
         * Update valves
         */
        $valves = Valve::where(function ($query) use ($room) {
            $query->where('room_id', $room->id)
                  ->orWhereNull('room_id');
        })->get();

        foreach ($valves as $v) {
            $v->room_id = null;
            $v->save();
        }

        /*
         * Update animals
         */
        $animals = Animal::where(function ($query) use ($room) {
            $query->where('room_id', $room->id)
                  ->orWhereNull('room_id');
        })->get();

        foreach ($animals as $a) {
            $a->room_id = null;
            $a->save();
        }

        $room->delete();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('rooms')
            ]
        ]);

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {

        if (Gate::denies('api-write:room')) {
            return $this->respondUnauthorized();
        }

        if (!$request->has('display_name')) {
            return $this->setStatusCode(422)
                        ->setErrorCode('104')
                        ->respondWithErrorDefaultMessage(['missing_fields' => 'display_name']);
        }

        /**
         * @var Room $room
         */
        $room = Room::create([
            'name' => $request->input('display_name'),
            'display_name' => $request->input('display_name')
        ]);

        $room->generateDefaultSuggestionSettings();

        $this->update($request, $room->id);

        return $this->setStatusCode(200)->respondWithData(
            [
                'id'    =>  $room->id
            ],
            [
                'redirect' => [
                    'uri'   => url('rooms/' . $room->id . '/edit')
                ]
            ]
        );

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {

        if (Gate::denies('api-write:room')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var Room $room
         */
        $room = Room::find($id);
        if (is_null($room)) {
            return $this->respondNotFound();
        }
        /*
         * Parse suggestions
         */
        if ($request->filled('suggestions')) {
            foreach ($request->input('suggestions') as $type=>$options) {
                $room->toggleSuggestions($type, $options['enabled'] != 'off');
                $room->setSuggestionSettings($type, $options['timeframe_start'], $options['timeframe_unit'], $options['threshold']);
            }
        }

        $this->updateModelProperties($room, $request, [
            'name', 'display_name'
        ]);

        if ($request->filled('notifications_enabled')) {
            $room->notifications_enabled = $request->input('notifications_enabled') != 'off';
        }

        $room->save();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('rooms')
            ]
        ]);

    }

    /**
     * Returns an array of sensor readings
     * filtered by type and grouped by
     * sensor reading group
     *
     * @param $id
     * @param $type
     * @return JsonResponse
     */
    public function sensorreadingsByType($id, $type)
    {

        if (Gate::denies('api-read')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var Room $room
         */
        $room = Room::with('physical_sensors')->find($id);

        if (!$room) {
            return $this->respondNotFound();
        }

        $data = $room->getSensorreadingsByType($type);

        return $this->respondWithData($data);

    }

    /**
     * Returns sensor readings
     * grouped by sensor reading group
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function sensorreadings(Request $request, $id)
    {

        if (Gate::denies('api-read')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var Room $room
         */
        $room = Room::find($id);

        if (!$room) {
            return $this->respondNotFound();
        }

        $query = $this->filter($request, Sensorreading::query());
        $logical_sensor_ids = [];

        foreach ($room->physical_sensors as $ps) {
            foreach ($ps->logical_sensors as $ls) {
                $logical_sensor_ids[] = $ls->id;
            }
        }

        $data = (new SensorreadingRepository())
                    ->getByLogicalSensor($query, $logical_sensor_ids)
                    ->get()
                    ->toArray();

        $data = array_map(function ($sr) {
            return [
                'value' => $sr['adjusted_value'],
                'read_at' => $sr['read_at'],
                'logical_sensor_name' => $sr['logical_sensor']['name'],
                'logical_sensor_id' => $sr['logical_sensor_id'],
                'value_type' => $sr['logical_sensor']['type']
            ];
        }, $data);

        return $this->setStatusCode(200)->respondWithData($data);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function infrastructure(Request $request, $id)
    {
        /**
         * @var Room $room
         */
        $room = Room::find($id);
        if (is_null($room)) {
            return $this->respondNotFound();
        }


        $physical_sensors = $this->filter($request, $room->physical_sensors()->getQuery())->get();
        foreach ($physical_sensors as &$p) {
            $p = (new GenericRepository($p))->show();
        }
        $physical_sensors = (new PhysicalSensorTransformer())->transformCollection($physical_sensors->toArray());

        return $this->respondWithData($physical_sensors);
    }

}
