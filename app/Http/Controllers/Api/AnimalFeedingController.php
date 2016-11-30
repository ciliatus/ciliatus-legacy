<?php

namespace App\Http\Controllers\Api;

use App\Animal;
use App\Events\AnimalFeedingUpdated;
use App\Http\Transformers\AnimalFeedingTransformer;
use App\Property;
use App\Repositories\AnimalFeedingRepository;
use Illuminate\Http\Request;
use Gate;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;

/**
 * Class AnimalFeedingController
 * @package App\Http\Controllers\Api
 */
class AnimalFeedingController extends ApiController
{

    /**
     * @var AnimalFeedingTransformer
     */
    protected $animalFeedingTransformer;


    /**
     * AnimalFeedingController constructor.
     * @param AnimalFeedingTransformer $_animalFeedingTransformer
     */
    public function __construct(AnimalFeedingTransformer $_animalFeedingTransformer)
    {
        parent::__construct();
        $this->animalFeedingTransformer = $_animalFeedingTransformer;
    }


    /**
     * @param $animal_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($animal_id)
    {
        if (Gate::denies('api-list')) {
            return $this->respondUnauthorized();
        }

        $animal = Animal::find($animal_id);
        if (is_null($animal)) {
            return $this->respondNotFound("Animal not found");
        }

        $feedings = [];
        foreach ($animal->feedings as $f) {
            $feedings[] = (new AnimalFeedingRepository($f))->show()->toArray();
        }

        return $this->respondWithData(
            $this->animalFeedingTransformer->transformCollection(
                $feedings
            )
        );

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $id)
    {
        if (Gate::denies('api-write:animal')) {
            return $this->respondUnauthorized();
        }

        $animal = Animal::find($id);
        if (is_null($animal)) {
            return $this->setStatusCode(404)->respondWithError('Animal not found');
        }

        $p = Property::create([
            'belongsTo_type' => 'Animal',
            'belongsTo_id' => $animal->id,
            'type' => 'AnimalFeeding',
            'name' => $request->input('meal_type'),
            'value' => $request->has('count') ? $request->input('count') : ''
        ]);

        $animal->save();

        broadcast(new AnimalFeedingUpdated($p));

        return $this->respondWithData([]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}