<?php

namespace App\Http\Controllers\Api;

use App\Animal;
use App\AnimalFeedingEvent;
use App\AnimalFeedingScheduleProperty;
use App\Events\AnimalFeedingEventDeleted;
use App\Events\AnimalFeedingEventUpdated;
use App\Events\AnimalFeedingSchedulePropertyDeleted;
use App\Events\AnimalFeedingSchedulePropertyUpdated;
use App\Events\AnimalUpdated;
use App\Property;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;

/**
 * Class AnimalFeedingEventController
 * @package App\Http\Controllers\Api
 */
class AnimalFeedingEventController extends ApiController
{

    /**
     * AnimalFeedingEventController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->errorCodeNamespace = '17';
    }

    /**
     * @param Request $request
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
            return $this->respondNotFound();
        }

        $feedings = $this->filter($request, $animal->feedings()->orderBy('created_at', 'DESC')->getQuery());

        return $this->respondTransformedAndPaginated(
            $request,
            $feedings
        );
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        return parent::default_show($request, $id);
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
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $id)
    {
        if (Gate::denies('api-write:animal_feeding')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var Animal $animal
         */
        $animal = Animal::find($id);
        if (is_null($animal)) {
            return $this->respondNotFound();
        }

        if ($request->filled('created_at')) {
            try {
                $created_at = Carbon::parse($request->input('created_at'));
            }
            catch (\Exception $ex) {
                return $this->setStatusCode(422)
                    ->setErrorCode('103')
                    ->respondWithErrorDefaultMessage(['timestamp' => 'created_at']);
            }
        }
        else {
            $created_at = Carbon::now();
        }

        /**
         * @var AnimalFeedingEvent $event
         */
        $event = AnimalFeedingEvent::create([
            'belongsTo_type' => 'Animal',
            'belongsTo_id' => $animal->id,
            'type' => 'AnimalFeeding',
            'name' => $request->input('meal_type'),
            'value' => $request->filled('count') ? $request->input('count') : '',
            'created_at' => $created_at
        ]);

        broadcast(new AnimalFeedingEventUpdated($event->fresh()));
        broadcast(new AnimalUpdated($animal));

        foreach ($animal->feeding_schedules as $fs) {
            broadcast(new AnimalFeedingSchedulePropertyUpdated($fs));
        }

        return $this->respondWithData([
            'id' => $event->id
        ]);
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
     * @param  \Illuminate\Http\Request $request
     * @param string $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $animal_id
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($animal_id, $id)
    {
        if (Gate::denies('api-write:animal_feeding')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var Animal $animal
         */
        $animal = Animal::find($animal_id);
        if (is_null($animal)) {
            return $this->respondNotFound();
        }

        /**
         * @var AnimalFeedingEvent $event
         */
        $event = $animal->feedings()->where('id', $id)->get()->first();
        if (is_null($event)) {
            return $this->respondNotFound();
        }

        broadcast(new AnimalFeedingEventDeleted($event));

        $event->delete();


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
            return $this->setStatusCode(422)
                        ->setErrorCode('104')
                        ->respondWithErrorDefaultMessage(['missing_fields' => 'name']);
        }

        if (is_null($type =
            Property::where([
                'belongsTo_type' => 'System',
                'belongsTo_id' => '00000000-0000-0000-0000-000000000000',
                'type' => 'AnimalFeedingType',
                'name' => $request->input('name')
            ])->get()->first()
        ))
        {

            $type = Property::create([
                'belongsTo_type' => 'System',
                'belongsTo_id' => '00000000-0000-0000-0000-000000000000',
                'type' => 'AnimalFeedingType',
                'name' => $request->input('name'),
                'value' => $request->input('name')
            ]);
        }

        return $this->respondWithData(
            [
                'id' => $type->id
            ],
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
        $schedules = AnimalFeedingScheduleProperty::where('name', $type->name)->get();

        foreach ($schedules as $s) {
            broadcast(new AnimalFeedingSchedulePropertyDeleted($s));
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