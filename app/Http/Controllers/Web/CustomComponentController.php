<?php

namespace App\Http\Controllers\Web;

use App\Controlunit;
use App\CustomComponent;
use App\CustomComponentType;
use App\Http\Controllers\Controller;
use Gate;
use Illuminate\Http\Request;

/**
 * Class CustomComponentController
 * @package App\Http\Controllers\Web
 */
class CustomComponentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
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
        if (Gate::denies('api-write:custom_component')) {
            return response()->view('errors.401', [], 401);
        }

        if (!$request->has('preset') || !isset($request->input('preset')['custom_component_type_id'])) {
            return response()->view('errors.422', [], 422);
        }
        $type = CustomComponentType::with('properties')->find($request->input('preset')['custom_component_type_id']);
        if (is_null($type)) {
            return response()->view('errors.422', [], 422);
        }

        $belongTo_Options = [];
        foreach (['Terrarium'] as $t) {
            $belongTo_Options[$t] = ('App\\' . $t)::get();
        }

        return view('custom_components.create', [
            'belongTo_Options' => $belongTo_Options,
            'controlunits' => Controlunit::get(),
            'preset' => $request->input('preset'),
            'type' => $type
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (Gate::denies('api-write:custom_component')) {
            return response()->view('errors.401', [], 401);
        }

        $component = CustomComponent::find($id);
        if (is_null($component)) {
            return response()->view('errors.404', [], 404);
        }

        return view('custom_components.show', [
            'custom_component' => $component
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Gate::denies('api-write:custom_component')) {
            return response()->view('errors.401', [], 401);
        }

        $component = CustomComponent::find($id);
        if (is_null($component)) {
            return response()->view('errors.404', [], 404);
        }

        $belongTo_Options = [];
        foreach (['Terrarium'] as $t) {
            $belongTo_Options[$t] = ('App\\' . $t)::get();
        }

        return view('custom_components.edit', [
            'belongTo_Options' => $belongTo_Options,
            'controlunits' => Controlunit::get(),
            'custom_component' => $component
        ]);
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
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delete($id)
    {
        if (Gate::denies('api-write:custom_component')) {
            return response()->view('errors.401', [], 401);
        }

        $component = CustomComponent::find($id);
        if (is_null($component)) {
            return response()->view('errors.404', [], 404);
        }

        return view('custom_components.delete', [
            'custom_component' => $component
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return void
     */
    public function destroy($id)
    {
        //
    }
}
