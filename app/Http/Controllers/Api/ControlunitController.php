<?php

namespace App\Http\Controllers\Api;

use App\Controlunit;
use App\Http\Transformers\ControlunitTransformer;
use Gate;
use Illuminate\Http\Request;

/**
 * Class ControlunitController
 * @package App\Http\Controllers
 */
class ControlunitController extends ApiController
{
    /**
     * @var ControlunitTransformer
     */
    protected $controlunitTransformer;

    /**
     * ControlunitController constructor.
     * @param ControlunitTransformer $_controlunitTransformer
     */
    public function __construct(ControlunitTransformer $_controlunitTransformer)
    {
        parent::__construct();
        $this->controlunitTransformer = $_controlunitTransformer;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if (Gate::denies('api-list')) {
            return $this->respondUnauthorized();
        }

        $controlunits = Controlunit::with('physical_sensors');

        $controlunits = $this->filter($request, $controlunits);

        /*
         * If raw is passed, pagination will be ignored
         * Permission api-list:raw is required
         */
        if ($request->has('raw') && Gate::allows('api-list:raw')) {

            return $this->setStatusCode(200)->respondWithData(
                $this->controlunitTransformer->transformCollection(
                    $controlunits->get()->toArray()
                )
            );
        }

        $controlunits = $controlunits->paginate(env('PAGINATION_PER_PAGE', 20));

        return $this->setStatusCode(200)->respondWithPagination(
            $this->controlunitTransformer->transformCollection(
                $controlunits->toArray()['data']
            ),
            $controlunits
        );
        
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {

        if (Gate::denies('api-read')) {
            return $this->respondUnauthorized();
        }

        $controlunit = Controlunit::with('physical_sensors')->find($id);

        if (!$controlunit) {
            return $this->respondNotFound('Controlunit not found');
        }

        return $this->setStatusCode(200)->respondWithData(
            $this->controlunitTransformer->transform(
                $controlunit->toArray()
            )
        );
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {

        if (Gate::denies('api-write:controlunit')) {
            return $this->respondUnauthorized();
        }

        $controlunit = Controlunit::find($id);
        if (is_null($controlunit)) {
            return $this->setStatusCode(422)->respondWithError('Controlunit not found');
        }

        $controlunit->delete();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('controlunits'),
                'delay' => 2000
            ]
        ]);

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        if (Gate::denies('api-write:controlunit')) {
            return $this->respondUnauthorized();
        }

        $controlunit = Controlunit::create();
        $controlunit->name = $request->input('name');
        $controlunit->save();

        return $this->setStatusCode(200)->respondWithData(
            [
                'id'    =>  $controlunit->id
            ],
            [
                'redirect' => [
                    'uri'   => url('controlunits/' . $controlunit->id . '/edit'),
                    'delay' => 100
                ]
            ]
        );

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {

        if (Gate::denies('api-write:controlunit')) {
            return $this->respondUnauthorized();
        }

        $controlunit = Controlunit::find($id);
        if (is_null($controlunit)) {
            return $this->setStatusCode(422)->respondWithError('Controlunit not found');
        }


        $controlunit->name = $request->input('name');

        $controlunit->save();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('controlunits'),
                'delay' => 1000
            ]
        ]);

    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchDesiredStates($id)
    {
        if (Gate::denies('api-fetch:desired_states')) {
            return $this->respondUnauthorized();
        }

        $controlunit = Controlunit::find($id);
        if (is_null($controlunit)) {
            return $this->setStatusCode(422)->respondWithError('Controlunit not found');
        }

        return $this->respondWithData($controlunit->fetchAndAckDesiredStates());
    }

}
