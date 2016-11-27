<?php

namespace App\Http\Controllers\Api;

use App\Http\Transformers\AnimalTransformer;
use App\Animal;
use App\Terrarium;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;


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
    public function index(Request $request)
    {
        if (Gate::denies('api-list')) {
            return $this->respondUnauthorized();
        }

        $animals = Animal::with('files');

        $animals = $this->filter($request, $animals)->get();

        foreach ($animals as &$a) {
            if (!is_null($a->terrarium_id))
                $a->terrarium_object = $a->terrarium;

            $age = $a->getAge();
            $a->age_value = $age[0];
            $a->age_unit = $age[1];
            $a->gender_icon = $a->gender_icon();

            $files = $a->files()->with('properties')->get();
            $a->default_background_filepath = null;
            foreach ($files as $f) {
                if ($f->property('is_default_background') == true) {
                    $a->default_background_filepath = $f->path_external();
                    break;
                }
            }
        }


        return $this->setStatusCode(200)->respondWithData(
            $this->animalTransformer->transformCollection(
                $animals->toArray()
            )
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

        $animal = Animal::find($id);

        if (!$animal) {
            return $this->respondNotFound('Animal not found');
        }

        if (!is_null($animal->terrarium_id))
            $animal->terrarium_object = $animal->terrarium;

        $age = $animal->getAge();
        $animal->age_value = $age[0];
        $animal->age_unit = $age[1];
        $animal->gender_icon = $animal->gender_icon();

        $files = $animal->files()->with('properties')->get();
        $animal->default_background_filepath = null;
        foreach ($files as $f) {
            if ($f->property('is_default_background') == true) {
                $animal->default_background_filepath = $f->path_external();
                break;
            }
        }

        return $this->setStatusCode(200)->respondWithData(
            $this->animalTransformer->transform(
                $animal->toArray()
            )
        );
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {

        if (Gate::denies('api-write:animal')) {
            return $this->respondUnauthorized();
        }

        $animal = Animal::find($id);
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
    public function store(Request $request)
    {

        if (Gate::denies('api-write:animal')) {
            return $this->respondUnauthorized();
        }

        $animal = Animal::create([
            'display_name' => $request->input('display_name')
        ]);

        return $this->setStatusCode(200)->respondWithData(
            [
                'id'    =>  $animal->id
            ],
            [
                'redirect' => [
                    'uri'   => url('animals/' . $animal->id . '/edit'),
                    'delay' => 100
                ]
            ]
        );

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {

        if (Gate::denies('api-write:animal')) {
            return $this->respondUnauthorized();
        }

        $animal = Animal::find($request->input('id'));
        if (is_null($animal)) {
            return $this->setStatusCode(422)->respondWithError('Animal not found');
        }

        if ($request->has('terrarium') && strlen($request->input('terrarium')) > 0) {
            $terrarium = Terrarium::find($request->input('terrarium'));
            if (is_null($terrarium)) {
                return $this->setStatusCode(422)->respondWithError('Terrarium not found');
            }
        }
        else {
            $terrarium = null;
        }

        $old_terrarium = $animal->terrarium;
        if (!is_null($terrarium)) {
            $animal->terrarium_id = $terrarium->id;
        }
        else {
            $animal->terrarium_id = null;
        }

        $animal->display_name = $request->input('displayname');
        $animal->common_name = $request->input('commonname');
        $animal->lat_name = $request->input('latinname');
        $animal->gender = $request->input('gender');


        if ($request->has('birthdate') && strlen($request->input('birthdate')) > 0) {
            try {
                $animal->birth_date = Carbon::parse($request->input('birthdate'));
            }
            catch (\Exception $ex) {
                return $this->setStatusCode(422)->respondWithError('Cannot parse date of birth');
            }
        }
        else {
            $animal->birth_date = null;
        }

        if ($request->has('deathdate') && strlen($request->input('deathdate')) > 0) {
            try {
                $animal->death_date = Carbon::parse($request->input('deathdate'));
            }
            catch (\Exception $ex) {
                return $this->setStatusCode(422)->respondWithError('Cannot parse date of death');
            }
        }
        else {
            $animal->death_date = null;
        }

        $animal->save();

        // trigger TerrariumUpdated events
        if (!is_null($old_terrarium)) {
            $old_terrarium->save();
        }
        if (!is_null($terrarium)) {
            $terrarium->save();
        }

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('animals'),
                'delay' => 1000
            ]
        ]);

    }

}
