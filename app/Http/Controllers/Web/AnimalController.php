<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Animal;
use App\Terrarium;
use Illuminate\Http\Request;

use App\Http\Requests;

/**
 * Class AnimalController
 * @package App\Http\Controllers
 */
class AnimalController extends Controller
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
    public function index()
    {
        return view('animals.index', [
            'animals' => Animal::orderBy('death_date')
                               ->orderBy('display_name')
                               ->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('animals.create', [
            'preset' => $request->input('preset')
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
        $animal = Animal::find($id);
        if (is_null($animal)) {
            return view('errors.404');
        }

        return view('animals.show', [
            'animal' => $animal
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
        $animal = Animal::find($id);

        if (is_null($animal)) {
            return view('errors.404');
        }

        $terraria = Terrarium::all();

        return view('animals.edit', [
            'animal'     => $animal,
            'terraria'        => $terraria
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
     * @return mixed
     */
    public function delete($id)
    {
        $animal = Animal::find($id);

        if (is_null($animal)) {
            return view('errors.404');
        }

        return view('animals.delete', [
            'animal'     => $animal
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
