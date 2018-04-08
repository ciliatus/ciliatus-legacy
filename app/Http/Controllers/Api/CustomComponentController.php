<?php

namespace App\Http\Controllers\Api;

use App\Controlunit;
use App\CustomComponent;
use App\CustomComponentType;
use App\Property;
use Gate;
use Illuminate\Http\Request;

/**
 * Class CustomComponentController
 * @package App\Http\Controllers\Api
 */
class CustomComponentController extends ApiController
{

    /**
     * CustomComponentController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->errorCodeNamespace = '21';
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if (Gate::denies('api-write:custom_component')) {
            return $this->respondUnauthorized();
        }

        if ($request->filled('controlunit') && is_null(Controlunit::find($request->input('controlunit')))) {
            return $this->respondRelatedModelNotFound(Controlunit::class);
        }

        if (is_null(CustomComponentType::find($request->input('type_id')))) {
            return $this->respondRelatedModelNotFound(CustomComponentType::class);
        }

        $component = new CustomComponent();
        $component->name = $request->input('name');
        $component->custom_component_type_id = $request->input('type_id');
        $component->controlunit_id = $request->filled('controlunit') ? $request->input('controlunit') : null;
        $component = $this->addBelongsTo($request, $component);

        if ($request->filled('properties')) {
            foreach($request->input('properties') as $id=>$prop) {
                $prop_template = Property::find($id);
                if (is_null($prop_template)) {
                    return $this->respondRelatedModelNotFound('Property<CustomComponentTypeProperty>');
                }

                Property::create([
                    'belongsTo_type' => 'CustomComponent',
                    'belongsTo_id' => $component->id,
                    'type' => 'CustomComponentProperty',
                    'name' => $prop_template->name,
                    'value' => $prop
                ]);
            }
        }

        $component->save();

        $this->update($request, $component->id);

        $component->resync_states();

        return $this->setStatusCode(200)->respondWithData([
            'id' => $component->id
        ], [
            'redirect' => [
                'uri'   => url('custom_components/' . $component->id)
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
     * Error Codes
     *  - 201: Generic component is corrupt
     *
     * @param  \Illuminate\Http\Request  $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        if (Gate::denies('api-write:custom_component')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var CustomComponent $component
         */
        $component = CustomComponent::find($id);
        if (is_null($component)) {
            return $this->respondNotFound();
        }

        $component = $this->addBelongsTo($request, $component);

        if ($request->filled('name')) {
            $component->name = $request->input('name');
        }
        if ($request->filled('controlunit')) {
            if (is_null(Controlunit::find($request->input('controlunit')))) {
                return $this->respondRelatedModelNotFound(Controlunit::class);
            }
            $component->controlunit_id = $request->input('controlunit');
        }

        if ($request->filled('properties')) {
            foreach($request->input('properties') as $id=>$value) {
                $component_property = $component->properties()->where('id', $id)->get()->first();
                if (is_null($component_property)) {
                    return $this->setStatusCode(422)
                                ->setErrorCode('201')
                                ->respondWithErrorDefaultMessage();
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
                'uri'   => url('custom_components/' . $component->id)
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
        if (Gate::denies('api-write:custom_component')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var CustomComponent $component
         */
        $component = CustomComponent::find($id);
        if (is_null($component)) {
            return $this->respondNotFound();
        }

        $component->delete();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('custom_components')
            ]
        ]);
    }
}
