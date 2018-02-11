<?php

namespace App\Http\Controllers\Api;

use App\Animal;
use App\AnimalFeedingEvent;
use App\AnimalFeedingScheduleProperty;
use App\Events\AnimalFeedingEventUpdated;
use App\Events\AnimalFeedingSchedulePropertyDeleted;
use App\Events\AnimalFeedingSchedulePropertyUpdated;
use App\Events\AnimalUpdated;
use App\Property;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;

/**
 * Class AnimalFeedingSchedulePropertyController
 * @package App\Http\Controllers\Api
 */
class AnimalFeedingSchedulePropertyController extends ApiController
{

    /**
     * AnimalFeedingSchedulePropertyController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param Request $request
     * @param null $animal_id
     * @return \Illuminate\Http\JsonResponse
     * @internal param $animal_id
     * @throws \ErrorException
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
     * @return void
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

        $p = AnimalFeedingScheduleProperty::create([
            'belongsTo_type' => 'Animal',
            'belongsTo_id' => $animal_id,
            'type' => 'AnimalFeedingSchedule',
            'name' => $request->input('meal_type'),
            'value' => $request->input('interval_days')
        ]);

        if ($request->filled('starts_at')) {
            Property::where('belongsTo_type', 'Property')
                    ->where('belongsTo_id', $p->id)
                    ->where('type', 'AnimalFeedingScheduleStartDate')
                    ->where('name', 'starts_at')
                    ->delete();

            Property::create([
                'belongsTo_type' => 'Property',
                'belongsTo_id' => $p->id,
                'type' => 'AnimalFeedingScheduleStartDate',
                'name' => 'starts_at',
                'value' => $request->input('starts_at')
            ]);
        }

        broadcast(new AnimalFeedingSchedulePropertyUpdated($p));
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
     * @param string $id
     * @return void
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param string $animal_id
     * @param string $id
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

        broadcast(new AnimalFeedingSchedulePropertyUpdated($afs));
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
     * @param $animal_id
     * @param string $id
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

        broadcast(new AnimalFeedingSchedulePropertyDeleted($afs->id));
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

        $e = AnimalFeedingEvent::create([
            'belongsTo_type' => 'Animal',
            'belongsTo_id' => $animal->id,
            'type' => 'AnimalFeeding',
            'name' => $afs->name,
            'value' => 1
        ]);

        broadcast(new AnimalFeedingEventUpdated($e));
        broadcast(new AnimalFeedingSchedulePropertyUpdated($afs));
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

        Property::where('belongsTo_type', 'Property')
                ->where('belongsTo_id', $afs->id)
                ->where('type', 'AnimalFeedingScheduleStartDate')
                ->where('name', 'starts_at')
                ->delete();

        Property::create([
            'belongsTo_type' => 'Property',
            'belongsTo_id' => $afs->id,
            'type' => 'AnimalFeedingScheduleStartDate',
            'name' => 'starts_at',
            'value' => Carbon::today()->addDays((int)$afs->value)->format('Y-m-d')
        ]);

        broadcast(new AnimalFeedingSchedulePropertyUpdated($afs));
        broadcast(new AnimalUpdated($animal));

        return $this->respondWithData([]);
    }
}