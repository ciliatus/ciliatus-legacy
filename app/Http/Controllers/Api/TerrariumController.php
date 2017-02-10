<?php

namespace App\Http\Controllers\Api;

use App\Animal;
use App\Http\Transformers\TerrariumTransformer;
use App\Repositories\SensorreadingRepository;
use App\Repositories\TerrariumRepository;
use App\Terrarium;
use App\Valve;
use Cache;
use Carbon\Carbon;
use DB;
use Gate;
use Illuminate\Database\Eloquent\Collection;
use Log;
use Illuminate\Http\Request;


/**
 * Class TerrariumController
 * @package App\Http\Controllers
 */
class TerrariumController extends ApiController
{
    /**
     * @var TerrariumTransformer
     */
    protected $terrariumTransformer;

    /**
     * TerrariumController constructor.
     * @param TerrariumTransformer $_terrariumTransformer
     */
    public function __construct(TerrariumTransformer $_terrariumTransformer)
    {
        parent::__construct();
        $this->terrariumTransformer = $_terrariumTransformer;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {

        if (Gate::denies('api-list')) {
            return $this->respondUnauthorized();
        }

        $history_to = $request->has('history_to') ? $request->input('history_to') : Carbon::now();
        $history_minutes = $request->has('history_minutes') ? $request->input('history_minutes') : env('TERRARIUM_DEFAULT_HISTORY_MINUTES', 120);

        $terraria = Terrarium::with('action_sequences')
                             ->with('animals')
                             ->with('files')
                             ->with('physical_sensors')
                             ->with('valves');

        $terraria = $this->filter($request, $terraria);


        /*
         * If raw is passed, pagination will be ignored
         * Permission api-list:raw is required
         */
        if ($request->has('raw') && Gate::allows('api-list:raw')) {
            $terraria = $terraria->get();
            foreach ($terraria as &$t) {
                $t = (new TerrariumRepository($t))->show($history_to, $history_minutes);
            }

            return $this->setStatusCode(200)->respondWithData(
                $this->terrariumTransformer->transformCollection(
                    $terraria->toArray()
                )
            );

        }

        $terraria = $terraria->paginate(env('PAGINATION_PER_PAGE', 20));

        foreach ($terraria->items() as &$t) {
            $t = (new TerrariumRepository($t))->show($history_to, $history_minutes);
        }

        return $this->setStatusCode(200)->respondWithPagination(
            $this->terrariumTransformer->transformCollection(
                $terraria->toArray()['data']
            ),
            $terraria
        );

    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {

        if (Gate::denies('api-read')) {
            return $this->respondUnauthorized();
        }

        $terrarium = Terrarium::with('action_sequences')
                                ->with('animals')
                                ->with('files')
                                ->with('physical_sensors')
                                ->with('valves')
                                ->find($id);

        if (!$terrarium) {
            return $this->respondNotFound('Terrarium not found');
        }

        $history_to = $request->has('history_to') ? $request->input('history_to') : null;
        $history_minutes = $request->has('history_minutes') ? $request->input('history_minutes') : null;

        return $this->setStatusCode(200)
                    ->respondWithData(
                        $this->terrariumTransformer
                             ->transform(
                                 (new TerrariumRepository($terrarium))->show($history_to, $history_minutes)
                                                                      ->toArray())
                    );
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

        $query = $this->filter($request, DB::table('sensorreadings'));

        $sensor_types = ['humidity_percent', 'temperature_celsius'];
        $data = [];

        foreach ($sensor_types as $st) {
            $logical_sensor_ids = [];
            foreach ($terrarium->physical_sensors as $ps) {
                foreach ($ps->logical_sensors()->where('type', $st)->get() as $ls) {
                    $logical_sensor_ids[] = $ls->id;
                }
            }

            $data[$st] = (new SensorreadingRepository())->getAvgByLogicalSensor(clone $query, $logical_sensor_ids)->get();

        }

        if ($request->has('csv')) {
            $data_arr = [];
            $csv_fields = [];
            $csv_fields['created_at'] = trans('labels.created_at');
            foreach ($data as $type=>$values) {
                $csv_fields[$type] = trans('labels.' . $type);
                foreach ($values as $reading) {
                    $data_arr[$reading->sensorreadinggroup_id]['created_at'] = $reading->created_at;
                    $data_arr[$reading->sensorreadinggroup_id][$type] = $reading->avg_rawvalue;
                }
            }

            $data = $this->convert('csv', $csv_fields, $data_arr);

        }

        return $this->setStatusCode(200)->respondWithData($data);
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
            'display_name' => $request->input('display_name')
        ]);

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
    public function update(Request $request)
    {

        if (Gate::denies('api-write:terrarium')) {
            return $this->respondUnauthorized();
        }

        $terrarium = Terrarium::find($request->input('id'));
        if (is_null($terrarium)) {
            return $this->respondNotFound('Terrarium not found');
        }


        /*
         * Update valves
         */
        if ($request->has('valves')) {
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
        else {
            $valves = Valve::where('terrarium_id', $terrarium->id)->get();
            foreach ($valves as $a) {
                $a->terrarium_id = null;
                $a->save();
            }
        }


        /*
         * Update animals
         */
        if ($request->has('animals')) {
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
        else {
            $animals = Animal::where('terrarium_id', $terrarium->id)->get();
            foreach ($animals as $a) {
                $a->terrarium_id = null;
                $a->save();
            }
        }

        $terrarium->name = $request->input('name');
        $terrarium->display_name = $request->input('display_name');
        $terrarium->notifications_enabled = $request->has('notifications_enabled');
        $terrarium->save();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('terraria'),
                'delay' => 1000
            ]
        ]);

    }

}
