<?php

namespace App\Http\Controllers\Api;

use App\Animal;
use App\Event;
use App\Events\AnimalFeedingDeleted;
use App\Events\AnimalFeedingScheduleDeleted;
use App\Events\AnimalFeedingUpdated;
use App\Events\AnimalUpdated;
use App\Http\Transformers\AnimalFeedingTransformer;
use App\Property;
use App\Repositories\AnimalFeedingRepository;
use Carbon\Carbon;
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
    public function index(Request $request, $animal_id)
    {
        if (Gate::denies('api-list')) {
            return $this->respondUnauthorized();
        }

        $animal = Animal::find($animal_id);
        if (is_null($animal)) {
            return $this->respondNotFound("Animal not found");
        }

        $feedings = $this->filter($request, $animal->feedings()->orderBy('created_at', 'DESC')->getQuery());

        return $this->respondTransformedAndPaginated(
            $request,
            $feedings,
            $this->animalFeedingTransformer,
            'AnimalFeedingRepository'
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
        if (Gate::denies('api-write:animal_feeding')) {
            return $this->respondUnauthorized();
        }

        $animal = Animal::find($id);
        if (is_null($animal)) {
            return $this->setStatusCode(404)->respondWithError('Animal not found');
        }

        $e = Event::create([
            'belongsTo_type' => 'Animal',
            'belongsTo_id' => $animal->id,
            'type' => 'AnimalFeeding',
            'name' => $request->input('meal_type'),
            'value' => $request->has('count') ? $request->input('count') : ''
        ]);

        if ($request->has('created_at')) {
            $e->created_at = Carbon::parse($request->input('created_at'));
            $e->save();
        }

        broadcast(new AnimalFeedingUpdated($e->fresh()));
        broadcast(new AnimalUpdated($animal));

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
     * @param  int  $animal_id
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($animal_id, $id)
    {
        if (Gate::denies('api-write:animal_feeding')) {
            return $this->respondUnauthorized();
        }

        $animal = Animal::find($animal_id);
        if (is_null($animal)) {
            return $this->respondNotFound('Animal not found');
        }

        $animal_feeding = $animal->feedings()->where('id', $id)->get()->first();
        if (is_null($animal_feeding)) {
            return $this->respondNotFound('Animal feeding not found');
        }

        $id = $animal_feeding->id;

        $animal_feeding->delete();

        broadcast(new AnimalFeedingDeleted($id));

        return $this->respondWithData([]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function types(Request $request)
    {
        if (Gate::denies('api-list')) {
            return $this->respondUnauthorized();
        }

        $ft = Property::where('type', 'AnimalFeedingType');

        $ft = $this->filter($request, $ft);

        $return = [];
        foreach ($ft->get() as $t) {
            $return[] = $t->name;
        }

        return $this->respondWithData($return);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store_type(Request $request)
    {
        if (Gate::denies('admin')) {
            return $this->respondUnauthorized();
        }

        if (!$this->checkInput(['name'], $request)) {
            return $this->setErrorCode(422)->respondWithError('Missing fields');
        }

        Property::create([
            'belongsTo_type' => 'System',
            'belongsTo_id' => '00000000-0000-0000-0000-000000000000',
            'type' => 'AnimalFeedingType',
            'name' => $request->input('name')
        ]);

        return $this->respondWithData([],
            [
                'redirect' => [
                    'uri' => url('categories#tab_feeding_types')
                ]
            ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete_type(Request $request, $id)
    {
        if (Gate::denies('admin')) {
            return $this->respondUnauthorized();
        }

        $type = Property::find($id);
        $schedules = Property::where('type', 'AnimalFeedingSchedule')->where('name', $type->name)->get();

        foreach ($schedules as $s) {
            broadcast(new AnimalFeedingScheduleDeleted($s->id));
            $s->delete();
        }
        $type->delete();

        return $this->respondWithData([],
            [
                'redirect' => [
                    'uri' => url('categories#tab_feeding_types')
                ]
            ]);
    }
}