<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Controlunit;
use App\Pump;
use App\Terrarium;
use App\Valve;
use Illuminate\Http\Request;

use App\Http\Requests;

class ValveController extends Controller
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
        return view('valves.index', [
            'valves' => Valve::get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('valves.create', [
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
        $valve = Valve::find($id);
        if (is_null($valve)) {
            return response()->view('errors.404', [], 404);
        }

        return view('valves.show', [
            'valve' => $valve
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
        $valve = Valve::find($id);

        if (is_null($valve)) {
            return response()->view('errors.404', [], 404);
        }

        $controlunits = Controlunit::all();
        $terraria = Terrarium::all();
        $pumps = Pump::all();

        return view('valves.edit', [
            'valve'         => $valve,
            'controlunits'  => $controlunits,
            'pumps'         => $pumps,
            'terraria'      => $terraria,
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

    public function delete($id)
    {
        $valve = Valve::find($id);

        if (is_null($valve)) {
            return response()->view('errors.404', [], 404);
        }

        return view('valves.delete', [
            'valve'     => $valve
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
