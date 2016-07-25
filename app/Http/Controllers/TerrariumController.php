<?php

namespace App\Http\Controllers;

use App\Animal;
use App\Http\Transformers\TerrariumTransformer;
use App\Terrarium;
use App\Valve;
use Cache;
use Gate;
use Request;


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
    public function index()
    {
        if (Gate::denies('api-list')) {
            return $this->respondUnauthorized();
        }

        $terraria = Terrarium::paginate(10);

        return $this->setStatusCode(200)->respondWithPagination($this->terrariumTransformer->transformCollection($terraria->toArray()['data']), $terraria);
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

        if (Cache::has('api-show-terrarium-' . $id)) {
            return $this->setStatusCode(200)->respondWithData($this->terrariumTransformer->transform(Cache::get('api-show-terrarium-' . $id)->toArray()));
        }

        $terrarium = Terrarium::with('physical_sensors', 'animals')->find($id);

        if (!$terrarium) {
            return $this->respondNotFound('Terrarium not found');
        }

        $terrarium->cooked_humidity_percent = $terrarium->getCurrentHumidity();
        $terrarium->cooked_temperature_celsius = $terrarium->getCurrentTemperature();

        $terrarium->temperature_history = implode(',',
            array_map(
                function($val) {
                    return round($val, 1);
                },
                array_column($terrarium->getSensorReadingsTemperature(180), 'avg_rawvalue')
            )
        );

        $terrarium->humidity_history = implode(',',
            array_map(
                function($val) {
                    return round($val, 1);
                },
                array_column($terrarium->getSensorReadingsHumidity(180), 'avg_rawvalue')
            )
        );

        Cache::add('api-show-terrarium-' . $id, $terrarium, env('CACHE_API_TERRARIUM_SHOW_DURATION') / 60);

        return $this->setStatusCode(200)->respondWithData($this->terrariumTransformer->transform($terrarium->toArray()));
    }


    public function destroy()
    {

        if (Gate::denies('api-write:terrarium')) {
            return $this->respondUnauthorized();
        }

        $data = Request::all();

        $terrarium = Terrarium::find($data['f_delete_terra_id']);
        if (is_null($terrarium)) {
            return $this->setStatusCode(422)->respondWithError('Terrarium not found');
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
    public function store()
    {

        if (Gate::denies('api-write:terrarium')) {
            return $this->respondUnauthorized();
        }

        $data = Request::all();

        $terrarium = Terrarium::create();
        $terrarium->friendly_name = $data['f_create_terra_displayname'];
        $terrarium->save();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('terraria/' . $terrarium->id . '/edit'),
                'delay' => 100
            ]
        ]);

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function update()
    {

        if (Gate::denies('api-write:terrarium')) {
            return $this->respondUnauthorized();
        }

        $data = Request::all();

        $terrarium = Terrarium::find($data['f_edit_terra_id']);
        if (is_null($terrarium)) {
            return $this->setStatusCode(422)->respondWithError('Terrarium not found');
        }


        /*
         * Update valves
         */
        if (isset($data['f_edit_terra_valves'])) {
            /*
             * check all valves first
             * so we don't fail after a few
             * and get inconsistent data
             */
            foreach ($data['f_edit_terra_valves'] as $a) {
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
                if (in_array($a->id, $data['f_edit_terra_valves'])) {
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
        if (isset($data['f_edit_terra_animals'])) {
            /*
             * check all animals first
             * so we don't fail after a few
             * and get inconsistent data
             */
            foreach ($data['f_edit_terra_animals'] as $a) {
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
                if (in_array($a->id, $data['f_edit_terra_animals'])) {
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

        $terrarium->name = $data['f_edit_terra_name'];
        $terrarium->friendly_name = $data['f_edit_terra_friendlyname'];
        $terrarium->save();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('terraria'),
                'delay' => 1000
            ]
        ]);

    }

}
