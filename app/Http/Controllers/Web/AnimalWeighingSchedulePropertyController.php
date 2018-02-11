<?php

namespace App\Http\Controllers\Web;

use App\Animal;
use Illuminate\Http\Request;

/**
 * Class AnimalWeighingScheduleController
 * @package App\Http\Controllers
 */
class AnimalWeighingSchedulePropertyController extends \App\Http\Controllers\Controller
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
     * @param $animal_id
     * @return void
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
        $animal = Animal::find($animal_id);
        if (is_null($animal)) {
            return view('error.404');
        }

        return view('animals.weighing_schedules.create', [
            'animal' => $animal
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $animal_id
     * @return void
     */
    public function store(Request $request, $animal_id)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param $animal_id
     * @param string $id
     * @return void
     */
    public function show($animal_id, $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $animal_id
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function edit($animal_id, $id)
    {
        $animal = Animal::find($animal_id);
        if (is_null($animal)) {
            return view('error.404');
        }

        $aws = $animal->weighing_schedules()->where('id', $id)->get()->first();
        if (is_null($aws)) {
            return view('error.404');
        }

        return view('animals.weighing_schedules.edit', [
            'animal' => $animal,
            'aws' => $aws
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $animal_id
     * @param string $id
     * @return void
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
            return view('error.404');
        }

        $aws = $animal->weighing_schedules()->where('id', $id)->get()->first();
        if (is_null($aws)) {
            return view('error.404');
        }

        return view('animals.weighing_schedules.delete', [
            'animal' => $animal,
            'aws' => $aws
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $animal_id
     * @param string $id
     * @return void
     */
    public function destroy($animal_id, $id)
    {
        //
    }
}
