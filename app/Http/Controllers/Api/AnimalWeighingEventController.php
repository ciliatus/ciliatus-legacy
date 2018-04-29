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

    /**
     * AnimalWeighingEventController constructor.
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->errorCodeNamespace = '19';
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
            $weighings = $this->filter($request, AnimalWeighingEvent::query());
        }
        else {
            $animal = Animal::find($id);
            if (is_null($animal)) {
                return $this->respondNotFound();
            }

            $weighings = $this->filter($request, $animal->weighings()->getQuery());
        }

        if ($request->filled('graph')) {
            $weighings = $weighings->orderBy('created_at')->get();

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

        return $this->respondTransformedAndPaginated(
            $request,
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
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $id)
    {
        if (Gate::denies('api-write:animal_weighing')) {
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

            $created_at = Carbon::now();
        }
        else {
            $created_at = Carbon::now();
        }

        /**
         * @var AnimalWeighingEvent $e
         */
        $e = AnimalWeighingEvent::create([
            'belongsTo_type' => 'Animal',
            'belongsTo_id' => $animal->id,
            'type' => 'AnimalWeighing',
            'name' => 'g',
            'value' => $request->input('weight'),
            'created_at' => $created_at
        ]);

        broadcast(new AnimalWeighingEventUpdated($e->fresh()));
        broadcast(new AnimalUpdated($animal));

        return $this->respondWithData([
            'id' => $e->id
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
        if (Gate::denies('api-write:animal_weighing')) {
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
         * @var AnimalWeighingEvent $animal_weighing
         */
        $animal_weighing = $animal->weighings()->where('id', $id)->get()->first();
        if (is_null($animal_weighing)) {
            return $this->respondNotFound();
        }

        broadcast(new AnimalWeighingEventDeleted($animal_weighing));

        $animal_weighing->delete();


        return $this->respondWithData([]);
    }
}