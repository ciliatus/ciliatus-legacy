<?php

namespace App\Http\Controllers;

use App\Controlunit;
use App\Http\Transformers\ControlunitTransformer;
use Cache;
use Gate;
use Request;


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
    public function index()
    {
        if (Gate::denies('api-list')) {
            return $this->respondUnauthorized();
        }

        $controlunits = Controlunit::paginate(10);

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

        if (Cache::has('api-show-controlunit-' . $id)) {
            return $this->setStatusCode(200)->respondWithData(
                $this->controlunitTransformer->transform(
                    Cache::get('api-show-controlunit-' . $id)->toArray()
                )
            );
        }

        $controlunit = Controlunit::with('physical_sensors', 'controlunits')->find($id);

        if (!$controlunit) {
            return $this->respondNotFound('Controlunit not found');
        }

        Cache::add('api-show-controlunit-' . $id, $controlunit, env('CACHE_API_CONTROLUNIT_SHOW_DURATION') / 60);

        return $this->setStatusCode(200)->respondWithData(
            $this->controlunitTransformer->transform(
                $controlunit->toArray()
            )
        );
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy()
    {

        if (Gate::denies('api-write:controlunit')) {
            return $this->respondUnauthorized();
        }

        $data = Request::all();

        $controlunit = Controlunit::find($data['f_delete_controlunits_id']);
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
    public function store()
    {

        if (Gate::denies('api-write:controlunit')) {
            return $this->respondUnauthorized();
        }

        $data = Request::all();

        $controlunit = Controlunit::create();
        $controlunit->name = $data['f_create_controlunit_name'];
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
    public function update()
    {

        if (Gate::denies('api-write:controlunit')) {
            return $this->respondUnauthorized();
        }

        $data = Request::all();

        $controlunit = Controlunit::find($data['f_edit_controlunit_id']);
        if (is_null($controlunit)) {
            return $this->setStatusCode(422)->respondWithError('Controlunit not found');
        }


        $controlunit->name = $data['f_edit_controlunit_name'];

        $controlunit->save();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('controlunits'),
                'delay' => 1000
            ]
        ]);

    }

}
