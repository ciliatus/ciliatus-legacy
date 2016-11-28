<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Animal;
use App\Terrarium;
use App\Valve;
use Illuminate\Http\Request;

use App\Http\Requests;

/**
 * Class TerrariumController
 * @package App\Http\Controllers
 */
class TerrariumController extends Controller
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
        return view('terraria.index', [
            'terraria' => Terrarium::get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('terraria.create', [
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
        $terrarium = Terrarium::find($id);
        if (is_null($terrarium)) {
            return view('errors.404');
        }

        return view('terraria.show', [
            'terrarium' => $terrarium
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
        $terrarium = Terrarium::find($id);

        if (is_null($terrarium)) {
            return view('errors.404');
        }

        $valves = Valve::where(function ($query) use ($terrarium) {
            $query->where('terrarium_id', $terrarium->id)
                ->orWhereNull('terrarium_id');
        })->get();

        $animals = Animal::where(function ($query) use ($terrarium) {
            $query->where('terrarium_id', $terrarium->id)
                ->orWhere(function ($inner_query) use ($terrarium) {
                    $inner_query->whereNull('terrarium_id')
                        ->whereNull('death_date');
                });
        })->get();

        return view('terraria.edit', [
            'terrarium'     => $terrarium,
            'valves'        => $valves,
            'animals'       => $animals
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
        $terrarium = Terrarium::find($id);

        if (is_null($terrarium)) {
            return view('errors.404');
        }

        return view('terraria.delete', [
            'terrarium'     => $terrarium
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
