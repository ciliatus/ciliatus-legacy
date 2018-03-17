<?php

namespace App\Http\Controllers\Api;

use App\CustomComponentType;
use App\Property;
use Gate;
use Illuminate\Http\Request;

/**
 * Class CustomComponentTypeController
 * @package App\Http\Controllers\Api
 */
class CustomComponentTypeController extends ApiController
{

    /**
     * CustomComponentTypeController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->errorCodeNamespace = '22';
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
     * @return void
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
        if (Gate::denies('api-write:custom_component_type')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var CustomComponentType $type
         */
        $type = CustomComponentType::create([
            'name_singular' => $request->input('name_singular'),
            'name_plural' => $request->input('name_plural'),
            'icon' => $request->input('icon')
        ]);

        if ($request->filled('property_name')) {
            foreach ($request->get('property_name') as $prop) {
                Property::create([
                    'belongsTo_type' => 'CustomComponentType',
                    'belongsTo_id' => $type->id,
                    'type' => 'CustomComponentTypeProperty',
                    'name' => $prop
                ]);
            }
        }

        if ($request->filled('default_intention_intention') && $request->filled('default_intention_type')) {
            for ($i = 0; $i < count($request->get('default_intention_intention')); $i++) {
                Property::create([
                    'belongsTo_type' => 'CustomComponentType',
                    'belongsTo_id' => $type->id,
                    'type' => 'CustomComponentTypeIntention',
                    'name' => $request->get('default_intention_type')[$i],
                    'value' => $request->get('default_intention_intention')[$i]
                ]);
            }
        }

        if ($request->filled('state')) {
            foreach ($request->get('state') as $state) {
                $p = Property::create([
                    'belongsTo_type' => 'CustomComponentType',
                    'belongsTo_id' => $type->id,
                    'type' => 'CustomComponentTypeState',
                    'name' => $state
                ]);

                if ($request->filled('default_running_state') && $request->input('default_running_state') == $state) {
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
                    'uri'   => url('generic_property_types/' . $type->id . '/edit')
                ]
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param string $id
     * @return void
     */
    public function show(Request $request, $id)
    {
        //
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
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        if (Gate::denies('api-write:custom_component_type')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var CustomComponentType $type
         */
        $type = CustomComponentType::find($id);
        if (is_null($type)) {
            return $this->respondNotFound();
        }

        $this->updateModelProperties($type, $request, [
            'name_singular', 'name_plural', 'icon'
        ]);

        /*
         * Keep existing, remove non existing and add new properties
         */
        if ($request->filled('property_name')) {
            foreach ($request->get('property_name') as $n) {
                if (is_null($type->properties()->where('name', $n)->get()->first())) {
                    Property::create([
                        'belongsTo_type' => 'CustomComponentType',
                        'belongsTo_id' => $type->id,
                        'type' => 'CustomComponentTypeProperty',
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
        if ($request->filled('state')) {
            foreach ($request->get('state') as $new_state) {
                $state = $type->states()->where('name', $new_state)->get()->first();
                if (is_null($state)) {
                    Property::create([
                        'belongsTo_type' => 'CustomComponentType',
                        'belongsTo_id' => $type->id,
                        'type' => 'CustomComponentTypeState',
                        'name' => $new_state
                    ]);
                }
            }

            foreach ($type->states as $s) {
                if (array_search($s->name, $request->get('state')) === false) {
                    $s->delete();
                }
                elseif ($request->filled('default_running_state') && $request->input('default_running_state') == $s->name) {
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
        if ($request->filled('default_intention_intention') && $request->filled('default_intention_type')) {
            foreach ($type->intentions as $intention) {
                $intention->delete();
            }

            for ($i = 0; $i < count($request->get('default_intention_intention')); $i++) {
                $intention = $request->get('default_intention_intention')[$i];
                $parameter = $request->get('default_intention_type')[$i];

                Property::create([
                    'belongsTo_type' => 'CustomComponentType',
                    'belongsTo_id' => $type->id,
                    'type' => 'CustomComponentTypeIntention',
                    'name' => $parameter,
                    'value' => $intention
                ]);
            }
        }

        foreach ($type->components as $component) {
            $component->resync_properties();
        }

        $type->save();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('custom_component_types')
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        if (Gate::denies('api-write:custom_component_type')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var CustomComponentType $type
         */
        $type = CustomComponentType::find($id);
        if (is_null($type)) {
            return $this->respondNotFound();
        }

        $type->delete();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('custom_component_types')
            ]
        ]);
    }
}
