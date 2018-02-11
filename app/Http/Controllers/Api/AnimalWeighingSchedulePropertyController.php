<?php

namespace App\Http\Controllers\Api;

use App\Animal;
use App\AnimalWeighingScheduleProperty;
use App\Events\AnimalUpdated;
use App\Events\AnimalWeighingSchedulePropertyDeleted;
use App\Events\AnimalWeighingSchedulePropertyUpdated;
use App\Property;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;

/**
 * Class AnimalWeighingController
 * @package App\Http\Controllers\Api
 */
class AnimalWeighingSchedulePropertyController extends ApiController
{

    /**
     * AnimalWeighingSchedulePropertyController constructor.
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

            $weighing_schedules = $this->filter($request, $animal->weighing_schedules()->getQuery());
        }
        else {
            $weighing_schedules = $this->filter(
                $request,
                AnimalWeighingScheduleProperty::query()
            );
        }

        return $this->respondTransformedAndPaginated(
            $request,
            $weighing_schedules
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
        if (Gate::denies('api-write:animal_weighing_schedule')) {
            return $this->respondUnauthorized();
        }

        $animal = Animal::find($animal_id);
        if (is_null($animal)) {
            return $this->respondNotFound("Animal not found");
        }

        $p = AnimalWeighingScheduleProperty::create([
            'belongsTo_type' => 'Animal',
            'belongsTo_id' => $animal_id,
            'type' => 'AnimalWeighingSchedule',
            'name' => 'g',
            'value' => $request->input('interval_days')
        ]);

        if ($request->filled('starts_at')) {
            Property::where('belongsTo_type', 'Property')
                ->where('belongsTo_id', $p->id)
                ->where('type', 'AnimalWeighingScheduleStartDate')
                ->where('name', 'starts_at')
                ->delete();

            Property::create([
                'belongsTo_type' => 'Property',
                'belongsTo_id' => $p->id,
                'type' => 'AnimalWeighingScheduleStartDate',
                'name' => 'starts_at',
                'value' => $request->input('starts_at')
            ]);
        }

        broadcast(new AnimalWeighingSchedulePropertyUpdated($p));
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
     * @param  string $animal_id
     * @param  string  $id
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

        $this->updateModelProperties($aws, $request, [
            'value' => 'interval_days'
        ]);

        $aws->name = 'g';
        $aws->save();

        broadcast(new AnimalWeighingSchedulePropertyUpdated($aws));
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

        broadcast(new AnimalWeighingSchedulePropertyDeleted($aws->id));
        broadcast(new AnimalUpdated($animal));

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
    public function skip($animal_id, $id)
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

        Property::where('belongsTo_type', 'Property')
            ->where('belongsTo_id', $aws->id)
            ->where('type', 'AnimalWeighingScheduleStartDate')
            ->where('name', 'starts_at')
            ->delete();

        Property::create([
            'belongsTo_type' => 'Property',
            'belongsTo_id' => $aws->id,
            'type' => 'AnimalWeighingScheduleStartDate',
            'name' => 'starts_at',
            'value' => Carbon::today()->addDays((int)$aws->value)->format('Y-m-d')
        ]);

        broadcast(new AnimalWeighingSchedulePropertyUpdated($aws));
        broadcast(new AnimalUpdated($animal));

        return $this->respondWithData([]);
    }
}