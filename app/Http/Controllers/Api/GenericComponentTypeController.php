<?php

namespace App\Http\Controllers\Api;

use App\GenericComponentType;
use App\Http\Transformers\GenericComponentTypeTransformer;
use App\Property;
use Gate;
use Illuminate\Http\Request;

use App\Http\Requests;

class GenericComponentTypeController extends ApiController
{
    /**
     * @var GenericComponentTypeTransformer
     */
    protected $genericComponentTypeTransformer;


    /**
     * GenericComponentTypeController constructor.
     * @param GenericComponentTypeTransformer $_genericComponentTypeTransformer
     */
    public function __construct(GenericComponentTypeTransformer $_genericComponentTypeTransformer)
    {
        parent::__construct();
        $this->genericComponentTypeTransformer = $_genericComponentTypeTransformer;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if (Gate::denies('api-list')) {
            return $this->respondUnauthorized();
        }

        $types = GenericComponentType::with('properties');

        $types = $this->filter($request, $types);


        /*
         * If raw is passed, pagination will be ignored
         * Permission api-list:raw is required
         */
        if ($request->has('raw') && Gate::allows('api-list:raw')) {

            return $this->setStatusCode(200)->respondWithData(
                $this->genericComponentTypeTransformer->transformCollection(
                    $types->get()->toArray()
                )
            );

        }

        $types = $types->paginate(env('PAGINATION_PER_PAGE', 20));

        return $this->setStatusCode(200)->respondWithPagination(
            $this->genericComponentTypeTransformer->transformCollection(
                $types->toArray()['data']
            ),
            $types
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if (Gate::denies('api-write:generic_component_type')) {
            return $this->respondUnauthorized();
        }

        $type = GenericComponentType::create([
            'name_singular' => $request->input('name_singular'),
            'name_plural' => $request->input('name_plural'),
            'icon' => $request->input('icon')
        ]);

        if ($request->has('property_name')) {
            foreach ($request->get('property_name') as $prop) {
                $p = Property::create([
                    'belongsTo_type' => 'GenericComponentType',
                    'belongsTo_id' => $type->id,
                    'type' => 'GenericComponentTypeProperty',
                    'name' => $prop
                ]);
            }
        }

        if ($request->has('state')) {
            foreach ($request->get('state') as $prop) {
                $p = Property::create([
                    'belongsTo_type' => 'GenericComponentType',
                    'belongsTo_id' => $type->id,
                    'type' => 'GenericComponentTypeState',
                    'name' => $prop
                ]);
            }
        }

        return $this->setStatusCode(200)->respondWithData(
            [
                'id'    =>  $type->id
            ],
            [
                'redirect' => [
                    'uri'   => url('generic_property_types/' . $type->id . '/edit'),
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
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        if (Gate::denies('api-write:generic_component_type')) {
            return $this->respondUnauthorized();
        }

        $type = GenericComponentType::find($id);
        if (is_null($type)) {
            return $this->respondNotFound();
        }

        if ($request->has('name_singular')) {
            $type->name_singular = $request->input('name_singular');
        }
        if ($request->has('name_plural')) {
            $type->name_plural = $request->input('name_plural');
        }
        if ($request->has('icon')) {
            $type->icon = $request->input('icon');
        }

        /*
         * Keep existing, remove non existing and add new properties
         */
        if ($request->has('property_name')) {
            foreach ($request->get('property_name') as $n) {
                if (is_null($type->properties()->where('name', $n)->get()->first())) {
                    Property::create([
                        'belongsTo_type' => 'GenericComponentType',
                        'belongsTo_id' => $type->id,
                        'type' => 'GenericComponentTypeProperty',
                        'name' => $n
                    ]);
                }
            }

            foreach ($type->properties as $s) {
                if (array_search($s->name, $request->get('property_name')) === false) {
                    $s->delete();
                }
            }
        }

        foreach ($type->components as $component) {
            $component->resync_properties();
        }

        /*
         * Keep existing, remove non existing and add new states
         */
        if ($request->has('state')) {
            foreach ($request->get('state') as $n) {
                if (is_null($type->states()->where('name', $n)->get()->first())) {
                    Property::create([
                        'belongsTo_type' => 'GenericComponentType',
                        'belongsTo_id' => $type->id,
                        'type' => 'GenericComponentTypeState',
                        'name' => $n
                    ]);
                }
            }

            foreach ($type->states as $s) {
                if (array_search($s->name, $request->get('state')) === false) {
                    $s->delete();
                }
            }
        }

        foreach ($type->components as $component) {
            $component->resync_states();
        }

        $type->save();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('generic_component_types'),
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
    public function destroy($id)
    {
        if (Gate::denies('api-write:generic_component_type')) {
            return $this->respondUnauthorized();
        }

        $type = GenericComponentType::find($id);
        if (is_null($type)) {
            return $this->respondNotFound();
        }

        $type->delete();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('generic_component_types'),
                'delay' => 1000
            ]
        ]);
    }
}
