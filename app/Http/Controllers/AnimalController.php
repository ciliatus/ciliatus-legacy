<?php

namespace App\Http\Controllers;

use App\Http\Transformers\AnimalTransformer;
use App\Animal;
use App\Terrarium;
use Gate;
use Request;


/**
 * Class AnimalController
 * @package App\Http\Controllers
 */
class AnimalController extends ApiController
{
    /**
     * @var AnimalTransformer
     */
    protected $animalTransformer;

    /**
     * AnimalController constructor.
     * @param AnimalTransformer $_animalTransformer
     */
    public function __construct(AnimalTransformer $_animalTransformer)
    {
        parent::__construct();
        $this->animalTransformer = $_animalTransformer;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        if (Gate::denies('api-list')) {
            return $this->respondUnauthorized();
        }

        $animals = Animal::paginate(10);

        return $this->setStatusCode(200)->respondWithPagination($this->animalTransformer->transformCollection($animals->toArray()['data']), $animals);
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

        if (Cache::has('api-show-animal-' . $id)) {
            return $this->setStatusCode(200)->respondWithData($this->animalTransformer->transform(Cache::get('api-show-animal-' . $id)->toArray()));
        }

        $animal = Animal::with('physical_sensors', 'animals')->find($id);

        if (!$animal) {
            return $this->respondNotFound('Animal not found');
        }

        Cache::add('api-show-animal-' . $id, $animal, env('CACHE_API_TERRARIUM_SHOW_DURATION') / 60);

        return $this->setStatusCode(200)->respondWithData($this->animalTransformer->transform($animal->toArray()));
    }


    public function destroy()
    {

        if (Gate::denies('api-write:animal')) {
            return $this->respondUnauthorized();
        }

        $data = Request::all();

        $animal = Animal::find($data['f_delete_animals_id']);
        if (is_null($animal)) {
            return $this->setStatusCode(422)->respondWithError('Animal not found');
        }

        $animal->delete();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('animals'),
                'delay' => 2000
            ]
        ]);

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {

        if (Gate::denies('api-write:animal')) {
            return $this->respondUnauthorized();
        }

        $data = Request::all();

        $animal = Animal::create();
        $animal->display_name = $data['f_create_animal_displayname'];
        $animal->save();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('animals/' . $animal->id . '/edit'),
                'delay' => 100
            ]
        ]);

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function update()
    {

        if (Gate::denies('api-write:animal')) {
            return $this->respondUnauthorized();
        }

        $data = Request::all();

        $animal = Animal::find($data['f_edit_animal_id']);
        if (is_null($animal)) {
            return $this->setStatusCode(422)->respondWithError('Animal not found');
        }

        $terrarium = Terrarium::find($data['f_edit_animal_terrarium']);
        if (is_null($terrarium)) {
            return $this->setStatusCode(422)->respondWithError('Terrarium not found');
        }

        $animal->display_name = $data['f_edit_animal_displayname'];
        $animal->common_name = $data['f_edit_animal_commonname'];
        $animal->lat_name = $data['f_edit_animal_latinname'];
        $animal->terrarium_id = $terrarium->id;

        $animal->save();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('animals'),
                'delay' => 1000
            ]
        ]);

    }

}
