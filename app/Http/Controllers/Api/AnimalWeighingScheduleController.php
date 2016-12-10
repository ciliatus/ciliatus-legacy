<?php

namespace App\Http\Controllers\Api;

use App\Animal;
use App\Events\AnimalWeighingScheduleDeleted;
use App\Events\AnimalWeighingScheduleUpdated;
use App\Http\Transformers\AnimalWeighingScheduleTransformer;
use App\Property;
use App\Repositories\AnimalWeighingRepository;
use App\Repositories\AnimalWeighingScheduleRepository;
use Carbon\Carbon;
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
    public function index(Request $request, $animal_id)
    {
        if (Gate::denies('api-list')) {
            return $this->respondUnauthorized();
        }

        $animal = Animal::find($animal_id);
        if (is_null($animal)) {
            return $this->respondNotFound("Animal not found");
        }

        $weighing_schedules = $this->filter($request, $animal->weighing_schedules());

        /*
         * If raw is passed, pagination will be ignored
         * Permission api-list:raw is required
         */
        if ($request->has('raw') && Gate::allows('api-list:raw')) {

            foreach ($weighing_schedules as &$fs) {
                $fs = (new AnimalWeighingScheduleRepository($fs, $animal))->show();
            }

            return $this->setStatusCode(200)->respondWithData(
                $this->animalWeighingScheduleTransformer->transformCollection(
                    $weighing_schedules->toArray()
                )
            );

        }

        $weighing_schedules = $weighing_schedules->paginate(env('PAGINATION_PER_PAGE', 20));

        foreach ($weighing_schedules->items() as &$fs) {
            $fs = (new AnimalWeighingScheduleRepository($fs, $animal))->show();
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
        if (Gate::denies('api-write:controlunit')) {
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
        $animal = Animal::find($animal_id);
        if (is_null($animal)) {
            return view('error.404');
        }

        $aws = $animal->weighing_schedules()->where('id', $id)->get()->first();
        if (is_null($aws)) {
            return view('error.404');
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
        $animal = Animal::find($animal_id);
        if (is_null($animal)) {
            return view('error.404');
        }

        $aws = $animal->weighing_schedules()->where('id', $id)->get()->first();
        if (is_null($aws)) {
            return view('error.404');
        }

        broadcast(new AnimalWeighingScheduleDeleted((clone $aws)));

        $aws->delete();

        return $this->respondWithData([], [
            'redirect' => [
                'uri'   => url('animals/' . $animal->id . '/edit'),
                'delay' => 1000
            ]
        ]);
    }
}