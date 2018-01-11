<?php

namespace App\Http\Controllers\Web;

use App\Controlunit;
use App\GenericComponent;
use App\GenericComponentType;
use App\Http\Controllers\Controller;
use App\Property;
use Gate;
use Illuminate\Http\Request;

use App\Http\Requests;

class GenericComponentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (Gate::denies('api-write:generic_component')) {
            return response()->view('errors.401', [], 401);
        }

        if (!$request->has('preset') || !isset($request->input('preset')['generic_component_type_id'])) {
            return response()->view('errors.422', [], 422);
        }
        $type = GenericComponentType::with('properties')->find($request->input('preset')['generic_component_type_id']);
        if (is_null($type)) {
            return response()->view('errors.422', [], 422);
        }

        $belongTo_Options = [];
        foreach (['Terrarium'] as $t) {
            $belongTo_Options[$t] = ('App\\' . $t)::get();
        }

        return view('generic_components.create', [
            'belongTo_Options' => $belongTo_Options,
            'controlunits' => Controlunit::get(),
            'preset' => $request->input('preset'),
            'type' => $type
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (Gate::denies('api-write:generic_component')) {
            return response()->view('errors.401', [], 401);
        }

        $component = GenericComponent::find($id);
        if (is_null($component)) {
            return response()->view('errors.404', [], 404);
        }

        return view('generic_components.show', [
            'generic_component' => $component
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Gate::denies('api-write:generic_component')) {
            return response()->view('errors.401', [], 401);
        }

        $component = GenericComponent::find($id);
        if (is_null($component)) {
            return response()->view('errors.404', [], 404);
        }

        $belongTo_Options = [];
        foreach (['Terrarium'] as $t) {
            $belongTo_Options[$t] = ('App\\' . $t)::get();
        }

        return view('generic_components.edit', [
            'belongTo_Options' => $belongTo_Options,
            'controlunits' => Controlunit::get(),
            'generic_component' => $component
        ]);
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
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delete($id)
    {
        if (Gate::denies('api-write:generic_component')) {
            return response()->view('errors.401', [], 401);
        }

        $component = GenericComponent::find($id);
        if (is_null($component)) {
            return response()->view('errors.404', [], 404);
        }

        return view('generic_components.delete', [
            'generic_component' => $component
        ]);
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
