<?php

namespace App\Http\Controllers\Api;

use App\Animal;
use App\AnimalFeedingScheduleProperty;
use App\Events\AnimalFeedingScheduleDeleted;
use App\Events\AnimalFeedingScheduleUpdated;
use App\Events\AnimalFeedingUpdated;
use App\Events\AnimalUpdated;
use App\Http\Transformers\AnimalFeedingSchedulePropertyTransformer;
use App\Property;
use App\Repositories\AnimalFeedingRepository;
use App\Repositories\AnimalFeedingScheduleRepository;
use Carbon\Carbon;
use App\Event;
use Illuminate\Http\Request;
use Gate;
use App\Http\Requests;

/**
 * Class AnimalFeedingController
 * @package App\Http\Controllers\Api
 */
class AnimalFeedingScheduleController extends ApiController
{


    /**
     * @var AnimalFeedingSchedulePropertyTransformer
     */
    protected $animalFeedingScheduleTransformer;


    /**
     * AnimalFeedingScheduleController constructor.
     * @param AnimalFeedingSchedulePropertyTransformer $_animalFeedingScheduleTransformer
     */
    public function __construct(AnimalFeedingSchedulePropertyTransformer $_animalFeedingScheduleTransformer)
    {
        parent::__construct();
        $this->animalFeedingScheduleTransformer = $_animalFeedingScheduleTransformer;
    }


    /**
     * @param Request $request
     * @param null $animal_id
     * @return \Illuminate\Http\JsonResponse
     * @internal param $animal_id
     */
    public function index(Request $request, $animal_id = null)
    {
        if (Gate::denies('api-list')) {
            return $this->respondUnauthorized();
        }

        if (!is_null($animal_id)) {
            $animal = Animal::find($animal_id);
            if (is_null($animal)) {
                return $this->respondNotFound("Animal not found");
            }
            $feeding_schedules = $this->filter($request, $animal->feeding_schedules()->getQuery());
        }
        else {
            $feeding_schedules = $this->filter(
                $request,
                AnimalFeedingScheduleProperty::query()
            );

        }

        return $this->respondTransformedAndPaginated(
            $request,
            $feeding_schedules
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
        if (Gate::denies('api-write:animal_feeding_schedule')) {
            return $this->respondUnauthorized();
        }

        $animal = Animal::find($animal_id);
        if (is_null($animal)) {
            return $this->respondNotFound("Animal not found");
        }

        $p = Property::create([
            'belongsTo_type' => 'Animal',
            'belongsTo_id' => $animal_id,
            'type' => 'AnimalFeedingSchedule',
            'name' => $request->input('meal_type'),
            'value' => $request->input('interval_days')
        ]);

        if ($request->has('starts_at')) {
            Property::create([
                'belongsTo_type' => 'Property',
                'belongsTo_id' => $p->id,
                'type' => 'AnimalFeedingScheduleStartDate',
                'name' => 'starts_at',
                'value' => $request->input('starts_at')
            ]);
        }

        broadcast(new AnimalFeedingScheduleUpdated($p));
        broadcast(new AnimalUpdated($animal));

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
        if (Gate::denies('api-write:animal_feeding_schedule')) {
            return $this->respondUnauthorized();
        }

        $animal = Animal::find($animal_id);
        if (is_null($animal)) {
            return $this->respondNotFound();
        }

        $afs = $animal->feeding_schedules()->where('id', $id)->get()->first();
        if (is_null($afs)) {
            return $this->respondNotFound();
        }

        $this->updateModelProperties($afs, $request,  [
            'name' => 'meal_type', 'value' => 'interval_days'
        ]);

        $afs->save();

        broadcast(new AnimalFeedingScheduleUpdated($afs));
        broadcast(new AnimalUpdated($animal));

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
        if (Gate::denies('api-write:animal_feeding_schedule')) {
            return $this->respondUnauthorized();
        }

        $animal = Animal::find($animal_id);
        if (is_null($animal)) {
            return $this->respondNotFound();
        }

        $afs = $animal->feeding_schedules()->where('id', $id)->get()->first();
        if (is_null($afs)) {
            return $this->respondNotFound();
        }

        $afs_properties = Property::where('belongsTo_type', 'Property')->where('belongsTo_id', $afs->id)->get();
        foreach ($afs_properties as $p) {
            $p->delete();
        }

        broadcast(new AnimalFeedingScheduleDeleted($afs->id));
        broadcast(new AnimalUpdated($animal));

        $afs->delete();

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
        if (Gate::denies('api-write:animal_feeding_schedule')) {
            return $this->respondUnauthorized();
        }

        $animal = Animal::find($animal_id);
        if (is_null($animal)) {
            return $this->respondNotFound();
        }

        $afs = $animal->feeding_schedules()->where('id', $id)->get()->first();
        if (is_null($afs)) {
            return $this->respondNotFound();
        }

        $e = Event::create([
            'belongsTo_type' => 'Animal',
            'belongsTo_id' => $animal->id,
            'type' => 'AnimalFeeding',
            'name' => $afs->name,
            'value' => 1
        ]);

        broadcast(new AnimalFeedingUpdated($e));
        broadcast(new AnimalFeedingScheduleUpdated($afs));
        broadcast(new AnimalUpdated($animal));

        return $this->respondWithData([]);
    }

    /**
     * @param $animal_id
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function skip($animal_id, $id)
    {
        if (Gate::denies('api-write:animal_feeding_schedule')) {
            return $this->respondUnauthorized();
        }

        $animal = Animal::find($animal_id);
        if (is_null($animal)) {
            return $this->respondNotFound();
        }

        $afs = $animal->feeding_schedules()->where('id', $id)->get()->first();
        if (is_null($afs)) {
            return $this->respondNotFound();
        }

        $p = Property::create([
            'belongsTo_type' => 'Property',
            'belongsTo_id' => $afs->id,
            'type' => 'AnimalFeedingScheduleStartDate',
            'name' => 'starts_at',
            'value' => Carbon::today()->addDays((int)$afs->value)->format('Y-m-d')
        ]);

        broadcast(new AnimalFeedingScheduleUpdated($afs));
        broadcast(new AnimalUpdated($animal));

        return $this->respondWithData([]);
    }
}