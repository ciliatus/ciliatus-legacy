<?php

namespace App\Http\Controllers\Api;

use App\ActionSequenceSchedule;
use App\Animal;
use App\Http\Transformers\GenericComponentTransformer;
use App\Http\Transformers\PhysicalSensorTransformer;
use App\Http\Transformers\TerrariumTransformer;
use App\Http\Transformers\ValveTransformer;
use App\Repositories\GenericRepository;
use App\Repositories\SensorreadingRepository;
use App\Repositories\TerrariumRepository;
use App\Sensorreading;
use App\Terrarium;
use App\Valve;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;


/**
 * Class TerrariumController
 * @package App\Http\Controllers
 */
class TerrariumController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \ErrorException
     */
    public function index(Request $request)
    {

        if (Gate::denies('api-list')) {
            return $this->respondUnauthorized();
        }

        $terraria = Terrarium::query();
        $terraria = $this->filter($request, $terraria);

        $repository_parameters = [
            'history_to'        => $request->filled('history_to') ?
                                    $request->input('history_to') :
                                    null,
            'history_minutes'   => $request->filled('history_minutes') ?
                                    $request->input('history_minutes') :
                                    env('TERRARIUM_DEFAULT_HISTORY_MINUTES', 180)
        ];

        return $this->respondTransformedAndPaginated(
            $request,
            $terraria,
            $repository_parameters
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

        $t = Terrarium::query();
        $t = $this->filter($request, $t);
        $t = $t->find($id);

        if (!$t) {
            return $this->respondNotFound('Terrarium not found');
        }

        $history_to = $request->filled('history_to') ? $request->input('history_to') : null;
        $history_minutes = $request->filled('history_minutes') ? $request->input('history_minutes') : env('TERRARIUM_DEFAULT_HISTORY_MINUTES', 180);

        return $this->setStatusCode(200)
                    ->respondWithData(
                        (new TerrariumTransformer())
                             ->transform((new TerrariumRepository($t))
                                         ->show($history_to, $history_minutes)
                                         ->toArray())
                    );
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {

        if (Gate::denies('api-write:terrarium')) {
            return $this->respondUnauthorized();
        }

        $terrarium = Terrarium::find($id);
        if (is_null($terrarium)) {
            return $this->respondNotFound('Terrarium not found');
        }


        /*
         * Update valves
         */
        $valves = Valve::where(function ($query) use ($terrarium) {
            $query->where('terrarium_id', $terrarium->id)
                ->orWhereNull('terrarium_id');
        })->get();

        foreach ($valves as $v) {
            $v->terrarium_id = null;
            $v->save();
        }


        /*
         * Update animals
         */
        $animals = Animal::where(function ($query) use ($terrarium) {
            $query->where('terrarium_id', $terrarium->id)
                ->orWhereNull('terrarium_id');
        })->get();

        foreach ($animals as $a) {
            $a->terrarium_id = null;
            $a->save();
        }

        $terrarium->delete();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('terraria'),
                'delay' => 2000
            ]
        ]);

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        if (Gate::denies('api-write:terrarium')) {
            return $this->respondUnauthorized();
        }

        $terrarium = Terrarium::create([
            'name' => $request->input('display_name'),
            'display_name' => $request->input('display_name')
        ]);

        $terrarium->generateDefaultSuggestionSettings();

        return $this->setStatusCode(200)->respondWithData(
            [
                'id'    =>  $terrarium->id
            ],
            [
                'redirect' => [
                    'uri'   => url('terraria/' . $terrarium->id . '/edit'),
                    'delay' => 100
                ]
            ]
        );

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {

        if (Gate::denies('api-write:terrarium')) {
            return $this->respondUnauthorized();
        }

        $terrarium = Terrarium::find($id);
        if (is_null($terrarium)) {
            return $this->respondNotFound('Terrarium not found');
        }


        /*
         * Update valves
         */
        if ($request->filled('valves')) {
            /*
             * check all valves first
             * so we don't fail after a few
             * and get inconsistent data
             */
            foreach ($request->input('valves') as $a) {
                $valve = Valve::find($a);
                if (is_null($valve)) {
                    return $this->setStatusCode(422)->respondWithError('Valve not found');
                }
            }

            /*
             * Get all possible valves:
             * all valves associated with the terrarium
             * or with no terrarium associated
             */
            $valves = Valve::where(function ($query) use ($terrarium) {
                $query->where('terrarium_id', $terrarium->id)
                    ->orWhereNull('terrarium_id');
            })->get();

            foreach ($valves as $a) {
                /*
                 * If valve was selected in form: update terrarium_id
                 * otherwise set it to null
                 */
                if (in_array($a->id, $request->input('valves'))) {
                    $a->terrarium_id = $terrarium->id;
                }
                else {
                    $a->terrarium_id = null;
                }
                $a->save();
            }
        }
        elseif ($request->exists('valves')) {
            $valves = Valve::where('terrarium_id', $terrarium->id)->get();
            foreach ($valves as $a) {
                $a->terrarium_id = null;
                $a->save();
            }
        }


        /*
         * Update animals
         */
        if ($request->filled('animals')) {
            /*
             * check all animals first
             * so we don't fail after a few
             * and get inconsistent data
             */
            foreach ($request->input('animals') as $a) {
                $animal = Animal::find($a);
                if (is_null($animal)) {
                    return $this->setStatusCode(422)->respondWithError('Animal not found');
                }
            }

            /*
             * Get all possible animals:
             * all animals associated with the terrarium
             * or with no terrarium associated
             */
            $animals = Animal::where(function ($query) use ($terrarium) {
                $query->where('terrarium_id', $terrarium->id)
                    ->orWhereNull('terrarium_id');
            })->get();

            foreach ($animals as $a) {
                /*
                 * If animal was selected in form: update terrarium_id
                 * otherwise set it to null
                 */
                if (in_array($a->id, $request->input('animals'))) {
                    $a->terrarium_id = $terrarium->id;
                }
                else {
                    $a->terrarium_id = null;
                }
                $a->save();
            }
        }
        elseif ($request->exists('animals')) {
            $animals = Animal::where('terrarium_id', $terrarium->id)->get();
            foreach ($animals as $a) {
                $a->terrarium_id = null;
                $a->save();
            }
        }

        /*
         * Parse suggestions
         */
        if ($request->filled('suggestions')) {
            foreach ($request->input('suggestions') as $type=>$options) {
                $terrarium->toggleSuggestions($type, $options['enabled'] != 'off');
                $terrarium->setSuggestionSettings($type, $options['timeframe_start'], $options['timeframe_unit'], $options['threshold']);
            }
        }

        $this->updateModelProperties($terrarium, $request, [
            'name', 'display_name'
        ]);

        if ($request->filled('notifications_enabled')) {
            $terrarium->notifications_enabled = $request->input('notifications_enabled') != 'off';
        }

        $terrarium->save();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('terraria'),
                'delay' => 1000
            ]
        ]);

    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateActionSequence(Request $request, $id)
    {
        if (Gate::denies('api-write:terrarium')) {
            return $this->respondUnauthorized();
        }

        $terrarium = Terrarium::find($id);
        if (is_null($terrarium)) {
            return $this->respondNotFound('Terrarium not found');
        }

        $runonce = false;
        if ($request->filled('runonce')) {
            if ($request->input('runonce') == 'On') {
                $runonce = true;
            }
        }

        $action_sequence = $terrarium->generateActionSequenceByTemplate(
            $request->input('template'),
            $request->input('duration_minutes'),
            $runonce
        );

        if (!$action_sequence) {
            return $this->setSTatusCode(422)->respondWithError('Could not genereate action sequence');
        }

        if ($request->filled('schedule_now')) {
            if ($request->get('schedule_now') == 'On') {
                $starts_at = Carbon::now()->addMinute(1);

                ActionSequenceSchedule::create([
                    'name' => $action_sequence->name . ' Schedule',
                    'runonce' => $runonce,
                    'starts_at' => $starts_at->hour . ':' . $starts_at->minute . ':00',
                    'action_sequence_id' => $action_sequence->id
                ]);
            }
        }

        return $this->respondWithData([
            'id' => $action_sequence->id
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function infrastructure(Request $request, $id)
    {
        $terrarium = Terrarium::find($id);
        if (is_null($terrarium)) {
            return $this->respondNotFound('Terrarium not found');
        }

        $valves = $this->filter($request, $terrarium->valves()->getQuery())->get();
        foreach ($valves as &$v) {
            $v = (new GenericRepository($v))->show();
        }
        $valves = (new ValveTransformer())->transformCollection($valves->toArray());


        $physical_sensors = $this->filter($request, $terrarium->physical_sensors()->getQuery())->get();
        foreach ($physical_sensors as &$p) {
            $p = (new GenericRepository($p))->show();
        }
        $physical_sensors = (new PhysicalSensorTransformer())->transformCollection($physical_sensors->toArray());


        $generic_components = $this->filter($request, $terrarium->generic_components()->getQuery())->get();
        foreach ($generic_components as &$gc) {
            $gc = (new GenericRepository($gc))->show();
        }
        $generic_components = (new GenericComponentTransformer())->transformCollection($generic_components->toArray());

        return $this->respondWithData(array_merge($valves, $physical_sensors, $generic_components));
    }

    /**
     * Returns an array of sensor readings
     * filtered by type and grouped by
     * sensor reading group
     *
     * @param $id
     * @param $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function sensorreadingsByType($id, $type)
    {

        if (Gate::denies('api-read')) {
            return $this->respondUnauthorized();
        }

        $terrarium = Terrarium::with('physical_sensors')->find($id);

        if (!$terrarium) {
            return $this->respondNotFound('Terrarium not found');
        }

        $data = $terrarium->getSensorreadingsByType($type);

        return $this->respondWithData($data);

    }

    /**
     * Returns sensor readings
     * grouped by sensor reading group
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function sensorreadings(Request $request, $id)
    {

        if (Gate::denies('api-read')) {
            return $this->respondUnauthorized();
        }

        $terrarium = Terrarium::find($id);

        if (!$terrarium) {
            return $this->respondNotFound('Terrarium not found');
        }

        $query = $this->filter($request, Sensorreading::query());
        $logical_sensor_ids = [];

        foreach ($terrarium->physical_sensors as $ps) {
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
                'value' => $sr['rawvalue'],
                'read_at' => $sr['read_at'],
                'logical_sensor_name' => $sr['logical_sensor']['name'],
                'logical_sensor_id' => $sr['logical_sensor_id'],
                'value_type' => $sr['logical_sensor']['type']
            ];
        }, $data);

        return $this->setStatusCode(200)->respondWithData($data);
    }

}
