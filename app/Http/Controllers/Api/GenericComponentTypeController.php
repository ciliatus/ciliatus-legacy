<?php

namespace App\Http\Controllers\Api;

use App\GenericComponentType;
use App\Property;
use Gate;
use Illuminate\Http\Request;

class GenericComponentTypeController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return parent::default_index($request);
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

        if ($request->has('default_intention_intention') && $request->has('default_intention_type')) {
            for ($i = 0; $i < count($request->get('default_intention_intention')); $i++) {
                $p = Property::create([
                    'belongsTo_type' => 'GenericComponentType',
                    'belongsTo_id' => $type->id,
                    'type' => 'GenericComponentTypeIntention',
                    'name' => $request->get('default_intention_intention')[$i],
                    'value' => $request->get('default_intention_type')[$i]
                ]);
            }
        }

        if ($request->has('state')) {
            foreach ($request->get('state') as $state) {
                $p = Property::create([
                    'belongsTo_type' => 'GenericComponentType',
                    'belongsTo_id' => $type->id,
                    'type' => 'GenericComponentTypeState',
                    'name' => $state
                ]);

                if ($request->has('default_running_state') && $request->input('default_running_state') == $state) {
                    $type->default_running_state_id = $p->id;
                    $type->save();
                }
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
    public function show(Request $request, $id)
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

        $this->updateModelProperties($type, $request, [
            'name_singular', 'name_plural', 'icon'
        ]);

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
            foreach ($request->get('state') as $new_state) {
                $state = $type->states()->where('name', $new_state)->get()->first();
                if (is_null($state)) {
                    $p = Property::create([
                        'belongsTo_type' => 'GenericComponentType',
                        'belongsTo_id' => $type->id,
                        'type' => 'GenericComponentTypeState',
                        'name' => $new_state
                    ]);
                }
            }

            foreach ($type->states as $s) {
                if (array_search($s->name, $request->get('state')) === false) {
                    $s->delete();
                }
                elseif ($request->has('default_running_state') && $request->input('default_running_state') == $s->name) {
                    $type->default_running_state_id = $s->id;
                }
            }
        }

        foreach ($type->components as $component) {
            $component->resync_states();
        }

        /*
         * Keep existing, remove non existing and add new intentions
         */
        if ($request->has('default_intention_intention') && $request->has('default_intention_type')) {
            foreach ($type->intentions as $intention) {
                $intention->delete();
            }

            for ($i = 0; $i < count($request->get('default_intention_intention')); $i++) {
                $intention = $request->get('default_intention_intention')[$i];
                $value = $request->get('default_intention_type')[$i];

                Property::create([
                    'belongsTo_type' => 'GenericComponentType',
                    'belongsTo_id' => $type->id,
                    'type' => 'GenericComponentTypeIntention',
                    'name' => $intention,
                    'value' => $value
                ]);
            }
        }

        foreach ($type->components as $component) {
            $component->resync_properties();
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
