<?php

namespace App\Http\Controllers\Api;

use App\Animal;
use App\Event;
use App\Events\AnimalUpdated;
use App\Events\AnimalWeighingUpdated;
use App\Http\Transformers\AnimalWeighingTransformer;
use App\Property;
use App\Repositories\AnimalWeighingRepository;
use Illuminate\Http\Request;
use Gate;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;

/**
 * Class AnimalWeighingController
 * @package App\Http\Controllers\Api
 */
class AnimalWeighingController extends ApiController
{

    /**
     * @var AnimalWeighingTransformer
     */
    protected $animalWeighingTransformer;


    /**
     * AnimalWeighingController constructor.
     * @param AnimalWeighingTransformer $_animalWeighingTransformer
     */
    public function __construct(AnimalWeighingTransformer $_animalWeighingTransformer)
    {
        parent::__construct();
        $this->animalWeighingTransformer = $_animalWeighingTransformer;
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

        $weighings = $this->filter($request, $animal->weighings());

        /*
         * If raw is passed, pagination will be ignored
         * Permission api-list:raw is required
         */
        if ($request->has('raw') && Gate::allows('api-list:raw')) {
            $weighings = $weighings->get();
            foreach ($weighings as &$f) {
                $f = (new AnimalWeighingRepository($f))->show()->toArray();
            }

            return $this->setStatusCode(200)->respondWithData(
                $this->animalWeighingTransformer->transformCollection(
                    $weighings->toArray()
                )
            );

        }

        if ($request->has('graph')) {
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
            $f = (new AnimalWeighingRepository($f))->show()->toArray();
        }

        return $this->setStatusCode(200)->respondWithPagination(
            $this->animalWeighingTransformer->transformCollection(
                $weighings->toArray()['data']
            ),
            $weighings
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
        if (Gate::denies('api-write:animal_weighing')) {
            return $this->respondUnauthorized();
        }

        $animal = Animal::find($id);
        if (is_null($animal)) {
            return $this->setStatusCode(404)->respondWithError('Animal not found');
        }

        $e = Event::create([
            'belongsTo_type' => 'Animal',
            'belongsTo_id' => $animal->id,
            'type' => 'AnimalWeighing',
            'name' => 'g',
            'value' => $request->input('weight')
        ]);

        broadcast(new AnimalWeighingUpdated($e));
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}