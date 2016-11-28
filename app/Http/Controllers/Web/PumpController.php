<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Controlunit;
use App\Pump;
use App\Terrarium;
use Illuminate\Http\Request;

use App\Http\Requests;

/**
 * Class PumpController
 * @package App\Http\Controllers
 */
class PumpController extends Controller
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
        return view('pumps.index', [
            'pumps' => Pump::get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('pumps.create', [
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
        $pump = Pump::find($id);
        if (is_null($pump)) {
            return view('errors.404');
        }

        return view('pumps.show', [
            'pump' => $pump
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
        $pump = Pump::find($id);

        if (is_null($pump)) {
            return view('errors.404');
        }

        $controlunits = Controlunit::all();
        $terraria = Terrarium::all();

        return view('pumps.edit', [
            'pump'     => $pump,
            'controlunits'        => $controlunits,
            'terraria'        => $terraria,
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
        $pump = Pump::find($id);

        if (is_null($pump)) {
            return view('errors.404');
        }

        return view('pumps.delete', [
            'pump'     => $pump
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
