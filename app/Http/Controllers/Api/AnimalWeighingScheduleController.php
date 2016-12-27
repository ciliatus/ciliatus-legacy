<?php

namespace App\Http\Controllers\Api;

use App\Animal;
use App\Events\AnimalWeighingScheduleDeleted;
use App\Events\AnimalWeighingScheduleUpdated;
use App\Events\AnimalWeighingUpdated;
use App\Http\Transformers\AnimalWeighingScheduleTransformer;
use App\Property;
use App\Repositories\AnimalWeighingRepository;
use App\Repositories\AnimalWeighingScheduleRepository;
use Carbon\Carbon;
use Event;
use Illuminate\Http\Request;
use Gate;
use App\Http\Requests;

/**
 * Class AnimalWeighingController
 * @package App\Http\Controllers\Api
 */
class AnimalWeighingScheduleController extends ApiController
{


    /**
     * @var AnimalWeighingScheduleTransformer
     */
    protected $animalWeighingScheduleTransformer;


    /**
     * AnimalWeighingScheduleController constructor.
     * @param AnimalWeighingScheduleTransformer $_animalWeighingScheduleTransformer
     */
    public function __construct(AnimalWeighingScheduleTransformer $_animalWeighingScheduleTransformer)
    {
        parent::__construct();
        $this->animalWeighingScheduleTransformer = $_animalWeighingScheduleTransformer;
    }


    /**
     * @param $animal_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, $animal_id = false)
    {
        if (Gate::denies('api-list')) {
            return $this->respondUnauthorized();
        }

        if ($animal_id == false) {
            $weighing_schedules = $this->filter(
                $request,
                Property::where('type', 'AnimalWeighingSchedule')
            );
        }
        else {
            $animal = Animal::find($animal_id);
            if (is_null($animal)) {
                return $this->respondNotFound("Animal not found");
            }

            $weighing_schedules = $this->filter($request, $animal->weighing_schedules());
        }


        /*
         * If raw is passed, pagination will be ignored
         * Permission api-list:raw is required
         */
        if ($request->has('raw') && Gate::allows('api-list:raw')) {
            $weighing_schedules = $weighing_schedules->get();
            foreach ($weighing_schedules as &$ws) {
                $ws = (new AnimalWeighingScheduleRepository($ws))->show();
            }

            return $this->setStatusCode(200)->respondWithData(
                $this->animalWeighingScheduleTransformer->transformCollection(
                    $weighing_schedules->toArray()
                )
            );

        }

        $weighing_schedules = $weighing_schedules->paginate(env('PAGINATION_PER_PAGE', 20));

        foreach ($weighing_schedules->items() as &$ws) {
            $ws = (new AnimalWeighingScheduleRepository($ws))->show();
        }

        return $this->setStatusCode(200)->respondWithPagination(
            $this->animalWeighingScheduleTransformer->transformCollection(
                $weighing_schedules->toArray()['data']
            ),
            $weighing_schedules
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
     * @param $animal_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $animal_id)
    {
        if (Gate::denies('api-write:animal_weighing_schedule')) {
            return $this->respondUnauthorized();
        }

        $animal = Animal::find($animal_id);
        if (is_null($animal)) {
            return $this->respondNotFound("Animal not found");
        }

        $p = Property::create([
            'belongsTo_type' => 'Animal',
            'belongsTo_id' => $animal_id,
            'type' => 'AnimalWeighingSchedule',
            'name' => 'g',
            'value' => $request->input('interval_days')
        ]);

        if ($request->has('starts_at')) {
            Property::create([
                'belongsTo_type' => 'Property',
                'belongsTo_id' => $p->id,
                'type' => 'AnimalWeighingScheduleStartDate',
                'name' => 'starts_at',
                'value' => $request->input('starts_at')
            ]);
        }

        broadcast(new AnimalWeighingScheduleUpdated($p));

        return $this->setStatusCode(200)->respondWithData(
            [
                'id'    =>  $p->id
            ],
            [
                'redirect' => [
                    'uri'   => url('animals/' . $animal_id),
                    'delay' => 100
                ]
            ]
        );
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
     * @param  int  $animal_id
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $animal_id, $id)
    {
        if (Gate::denies('api-write:animal_weighing_schedule')) {
            return $this->respondUnauthorized();
        }

        $animal = Animal::find($animal_id);
        if (is_null($animal)) {
            return $this->respondNotFound();
        }

        $aws = $animal->weighing_schedules()->where('id', $id)->get()->first();
        if (is_null($aws)) {
            return $this->respondNotFound();
        }

        $aws->name = 'g';
        $aws->value = $request->input('interval_days');
        $aws->save();

        broadcast(new AnimalWeighingScheduleUpdated($aws));

        return $this->respondWithData([], [
            'redirect' => [
                'uri'   => url('animals/' . $animal->id . '/edit'),
                'delay' => 1000
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($animal_id, $id)
    {
        if (Gate::denies('api-write:animal_weighing_schedule')) {
            return $this->respondUnauthorized();
        }

        $animal = Animal::find($animal_id);
        if (is_null($animal)) {
            return $this->respondNotFound();
        }

        $aws = $animal->weighing_schedules()->where('id', $id)->get()->first();
        if (is_null($aws)) {
            return $this->respondNotFound();
        }

        broadcast(new AnimalWeighingScheduleDeleted($aws->id));

        $aws->delete();

        return $this->respondWithData([], [
            'redirect' => [
                'uri'   => url('animals/' . $animal->id . '/edit'),
                'delay' => 1000
            ]
        ]);
    }

    /**
     * @param $animal_id
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function done($animal_id, $id)
    {
        if (Gate::denies('api-write:animal_weighing_schedule')) {
            return $this->respondUnauthorized();
        }

        $animal = Animal::find($animal_id);
        if (is_null($animal)) {
            return $this->respondNotFound();
        }

        $afs = $animal->weighing_schedules()->where('id', $id)->get()->first();
        if (is_null($afs)) {
            return $this->respondNotFound();
        }

        $e = Event::create([
            'belongsTo_type' => 'Animal',
            'belongsTo_id' => $animal->id,
            'type' => 'AnimalWeighing',
            'name' => $afs->name
        ]);

        broadcast(new AnimalWeighingUpdated($e));
        broadcast(new AnimalWeighingScheduleUpdated($afs));

        return $this->respondWithData([]);
    }

    /**
     * @param $animal_id
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function skip($animal_id, $id)
    {
        if (Gate::denies('api-write:animal_weighing_schedule')) {
            return $this->respondUnauthorized();
        }

        $animal = Animal::find($animal_id);
        if (is_null($animal)) {
            return $this->respondNotFound();
        }

        $afs = $animal->weighing_schedules()->where('id', $id)->get()->first();
        if (is_null($afs)) {
            return $this->respondNotFound();
        }

        $p = Property::create([
            'belongsTo_type' => 'Property',
            'belongsTo_id' => $afs->id,
            'type' => 'AnimalWeighingScheduleStartDate',
            'name' => 'starts_at',
            'value' => Carbon::today()->addDays((int)$afs->value)->format('Y-m-d')
        ]);

        broadcast(new AnimalWeighingScheduleUpdated($afs));

        return $this->respondWithData([]);
    }
}