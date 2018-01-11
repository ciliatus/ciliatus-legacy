<?php

namespace App\Http\Controllers\Web;

use App\Animal;
use App\Property;
use Gate;
use Illuminate\Http\Request;

use App\Http\Requests;

/**
 * Class AnimalWeighingEventController
 * @package App\Http\Controllers
 */
class AnimalWeighingEventController extends \App\Http\Controllers\Controller
{

    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($animal_id)
    {
        //
    }


    /**
     * Show the form for creating a new resource.
     *
     * @param $animal_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($animal_id)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $animal_id)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($animal_id, $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($animal_id, $id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $animal_id, $id)
    {
        //
    }

    /**
     * @param $animal_id
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delete($animal_id, $id)
    {
        $animal = Animal::find($animal_id);
        if (is_null($animal)) {
            return response()->view('errors.404', [], 404);
        }

        $animal_weighing = $animal->weighings()->where('id', $id)->get()->first();
        if (is_null($animal_weighing)) {
            return response()->view('errors.404', [], 404);
        }

        return view('animals.weighings.delete', [
            'animal' => $animal,
            'animal_weighing' => $animal_weighing
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($animal_id, $id)
    {
        //
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit_types()
    {
        if (Gate::denies('admin')) {
            return response()->view('errors.401', [], 401);
        }

        $types = Property::where('type', 'AnimalWeighingType')->get();

        return view('animals.weighings.edit_types', [
            'types' => $types
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create_type()
    {
        if (Gate::denies('admin')) {
            return response()->view('errors.401', [], 401);
        }

        return view('animals.weighings.create_type');
    }
}
