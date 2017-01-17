<?php

namespace App\Http\Controllers\Api;

use App\Event;
use App\Http\Transformers\AnimalCaresheetTransformer;
use App\Http\Transformers\AnimalTransformer;
use App\Animal;
use App\Property;
use App\Repositories\AnimalRepository;
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

        $animals = Animal::query();
        $animals = $this->filter($request, $animals);

        /*
         * If raw is passed, pagination will be ignored
         * Permission api-list:raw is required
         */
        if ($request->has('raw') && Gate::allows('api-list:raw')) {
            $animals = $animals->get();
            foreach ($animals as &$t) {
                $t = (new AnimalRepository($t))->show();
            }

            return $this->setStatusCode(200)->respondWithData(
                $this->animalTransformer->transformCollection(
                    $animals->toArray()
                )
            );

        }

        $animals = $animals->paginate(env('PAGINATION_PER_PAGE', 20));

        foreach ($animals->items() as &$a) {
            $a = (new AnimalRepository($a))->show();
        }

        return $this->setStatusCode(200)->respondWithPagination(
            $this->animalTransformer->transformCollection(
                $animals->toArray()['data']
            ),
            $animals
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

        $animal = (new AnimalRepository())->show($id);

        if (!$animal) {
            return $this->respondNotFound('Animal not found');
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
            return $this->setStatusCode(404)->respondWithError('Animal not found');
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

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store_caresheet(Request $request)
    {
        if (Gate::denies('api-write:caresheet')) {
            return $this->respondUnauthorized();
        }

        $animal = Animal::find($this->getBelongsTo($request)['belongsTo_id']);
        if (is_null($animal)) {
            return $this->respondNotFound('Animal not found.');
        }

        $caresheet = $animal->generate_caresheet();

        return $this->respondWithData([], [
            'redirect' => [
                'uri' => url('animals/' . $animal->id . '/caresheets/' . $caresheet->id)
            ]
        ]);

    }

    /**
     * @param $animal_id
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete_caresheet($animal_id, $id)
    {
        if (Gate::denies('api-write:caresheet')) {
            return $this->respondUnauthorized();
        }

        $animal = Animal::find($animal_id);
        if (is_null($animal)) {
            return $this->respondNotFound();
        }

        $animal->delete_caresheet($id);

        return $this->respondWithData([], [
            'redirect' => [
                'uri' => url('animals/' . $animal->id . '/#tab_caresheets')
            ]
        ]);

    }

    /**
     * @param $animal_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function caresheets($animal_id)
    {
        $animal = Animal::find($animal_id);
        if (is_null($animal)) {
            return view('errors.404');
        }

        return $this->respondWithData(
            (new AnimalCaresheetTransformer())->transformCollection(
                $animal->caresheets->toArray()
            )
        );
    }

}
