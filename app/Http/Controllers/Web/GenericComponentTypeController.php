<?php

namespace App\Http\Controllers\Web;

use App\GenericComponentType;
use App\Http\Controllers\Controller;
use Gate;
use Illuminate\Http\Request;

use App\Http\Requests;

class GenericComponentTypeController extends Controller
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
    public function create()
    {
        if (Gate::denies('api-write:generic_component_type')) {
            return view('errors.401');
        }

        return view('generic_components.types.create');
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
        $type = GenericComponentType::find($id);
        if (is_null($type)) {
            return view('errors.404');
        }

        return view('generic_components.types.show', [
            'generic_component_type' => $type
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
        if (Gate::denies('api-write:generic_component_type')) {
            return view('errors.401');
        }

        $type = GenericComponentType::find($id);
        if (is_null($type)) {
            return view('errors.404');
        }

        return view('generic_components.types.edit', [
            'generic_component_type' => $type
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
        if (Gate::denies('api-write:generic_component_type')) {
            return view('errors.401');
        }

        $type = GenericComponentType::find($id);
        if (is_null($type)) {
            return view('errors.404');
        }

        return view('generic_components.types.delete', [
            'generic_component_type' => $type
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
