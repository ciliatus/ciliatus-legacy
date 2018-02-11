<?php

namespace App\Http\Controllers\Api;

use App\Controlunit;
use App\GenericComponent;
use App\GenericComponentType;
use App\Property;
use Gate;
use Illuminate\Http\Request;

/**
 * Class GenericComponentController
 * @package App\Http\Controllers\Api
 */
class GenericComponentController extends ApiController
{

    /**
     * GenericComponentController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \ErrorException
     */
    public function index(Request $request)
    {
        return parent::default_index($request);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \ErrorException
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if (Gate::denies('api-write:generic_component')) {
            return $this->respondUnauthorized();
        }

        if ($request->filled('controlunit') && is_null(Controlunit::find($request->input('controlunit')))) {
            return $this->setStatusCode(422)->respondWithError("Controlunit not found.");
        }

        if (is_null(GenericComponentType::find($request->input('type_id')))) {
            return $this->setStatusCode(422)->respondWithError("GenericComponentType not found.");
        }

        $component = GenericComponent::create([
            'name' => $request->input('name'),
            'generic_component_type_id' => $request->input('type_id'),
            'controlunit_id' => $request->filled('controlunit') ? $request->input('controlunit') : null
        ]);

        $component = $this->addBelongsTo($request, $component);

        if ($request->filled('properties')) {
            foreach($request->input('properties') as $id=>$prop) {
                $prop_template = Property::find($id);
                if (is_null($prop_template)) {
                    return $this->setStatusCode(422)->respondWithError('Property type not found');
                }

                Property::create([
                    'belongsTo_type' => 'GenericComponent',
                    'belongsTo_id' => $component->id,
                    'type' => 'GenericComponentProperty',
                    'name' => $prop_template->name,
                    'value' => $prop
                ]);
            }
        }

        $component->save();

        $component->resync_states();

        return $this->setStatusCode(200)->respondWithData([
            'id' => $component->id
        ], [
            'redirect' => [
                'uri'   => url('generic_components/' . $component->id),
                'delay' => 1000
            ]
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
     * @param  \Illuminate\Http\Request  $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        if (Gate::denies('api-write:generic_component')) {
            return $this->respondUnauthorized();
        }

        $component = GenericComponent::find($id);
        if (is_null($component)) {
            return $this->respondNotFound();
        }

        $component = $this->addBelongsTo($request, $component);

        if ($request->filled('name')) {
            $component->name = $request->input('name');
        }
        if ($request->filled('controlunit')) {
            if (is_null(Controlunit::find($request->input('controlunit')))) {
                return $this->setStatusCode(422)->respondWithError("Controlunit not found.");
            }
            $component->controlunit_id = $request->input('controlunit');
        }

        if ($request->filled('properties')) {
            foreach($request->input('properties') as $id=>$value) {
                $component_property = $component->properties()->where('id', $id)->get()->first();
                if (is_null($component_property)) {
                    return $this->setStatusCode(422)->respondWithError("Generic Component is corrupted. Call resync methods.");
                }
                $component_property->value = $value;
                $component_property->save();
            }
        }

        $this->updateExternalProperties($component, $request, [
            'ControlunitConnectivity' => [
                'bus_type', 'i2c_address', 'i2c_multiplexer_address', 'i2c_multiplexer_port', 'gpio_pin'
            ]
        ]);

        $component->save();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('generic_components/' . $component->id),
                'delay' => 1000
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if (Gate::denies('api-write:generic_component')) {
            return $this->respondUnauthorized();
        }

        $component = GenericComponent::find($id);
        if (is_null($component)) {
            return $this->respondNotFound();
        }

        $component->delete();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('generic_components'),
                'delay' => 1000
            ]
        ]);
    }
}
