<?php

namespace App\Http\Controllers\Api;

use App\Animal;
use App\AnimalWeighingEvent;
use App\Events\AnimalUpdated;
use App\Events\AnimalWeighingEventDeleted;
use App\Events\AnimalWeighingEventUpdated;
use App\Http\Transformers\AnimalWeighingEventTransformer;
use App\Repositories\AnimalWeighingEventRepository;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;

/**
 * Class AnimalWeighingEventController
 * @package App\Http\Controllers\Api
 */
class AnimalWeighingEventController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
    }


    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, $id = null)
    {
        if (Gate::denies('api-list')) {
            return $this->respondUnauthorized();
        }

        if (is_null($id)) {
            $weighings = $this->filter($request, AnimalWeighingEvent::orderBy('created_at', 'DESC')->getQuery());
        }
        else {
            $animal = Animal::find($id);
            if (is_null($animal)) {
                return $this->respondNotFound("Animal not found");
            }

            $weighings = $this->filter($request, $animal->weighings()->orderBy('created_at', 'DESC')->getQuery());
        }


        /*
         * If raw is passed, pagination will be ignored
         * Permission api-list:raw is required
         */
        if ($request->filled('raw') && Gate::allows('api-list:raw')) {
            $weighings = $weighings->get();
            foreach ($weighings as &$f) {
                $f = (new AnimalWeighingEventRepository($f))->show()->toArray();
            }

            return $this->setStatusCode(200)->respondWithData(
                (new AnimalWeighingEventTransformer())->transformCollection(
                    $weighings->toArray()
                )
            );

        }

        if ($request->filled('graph')) {
            $weighings = $weighings->get();

            $return = [
                'columns' => [
                    [
                        'type' => 'date',
                        'name' => 'X'
                    ],
                    [
                        'type' => 'number',
                        'name' => 'g'
                    ]
                ],
                'rows' => []
            ];

            foreach ($weighings as $weight) {
                $return['rows'][] = [
                    $weight->created_at->toDateString(),
                    (int)$weight->value
                ];
            }

            return $this->respondWithData($return);
        }

        $weighings = $weighings->paginate(env('PAGINATION_PER_PAGE', 20));

        foreach ($weighings->items() as &$f) {
            $f = (new AnimalWeighingEventRepository($f))->show()->toArray();
        }

        return $this->setStatusCode(200)->respondWithPagination(
            (new AnimalWeighingEventTransformer())->transformCollection(
                $weighings->toArray()['data']
            ),
            $weighings
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $id)
    {
        if (Gate::denies('api-write:animal_weighing')) {
            return $this->respondUnauthorized();
        }

        $animal = Animal::find($id);
        if (is_null($animal)) {
            return $this->setStatusCode(404)->respondWithError('Animal not found');
        }

        $e = AnimalWeighingEvent::create([
            'belongsTo_type' => 'Animal',
            'belongsTo_id' => $animal->id,
            'type' => 'AnimalWeighing',
            'name' => 'g',
            'value' => $request->input('weight')
        ]);

        if ($request->filled('created_at')) {
            $e->created_at = Carbon::parse($request->input('created_at'));
            $e->save();
        }

        broadcast(new AnimalWeighingEventUpdated($e->fresh()));
        broadcast(new AnimalUpdated($animal));

        return $this->respondWithData([
            'id' => $e->id
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
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
     * @param  int $id
     * @return void
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
        if (Gate::denies('api-write:animal_weighing')) {
            return $this->respondUnauthorized();
        }

        $animal = Animal::find($animal_id);
        if (is_null($animal)) {
            return $this->respondNotFound('Animal not found');
        }

        $animal_weighing = $animal->weighings()->where('id', $id)->get()->first();
        if (is_null($animal_weighing)) {
            return $this->respondNotFound('Animal weighing not found');
        }

        $id = $animal_weighing->id;

        $animal_weighing->delete();

        broadcast(new AnimalWeighingEventDeleted($id));

        return $this->respondWithData([]);
    }
}