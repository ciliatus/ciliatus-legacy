<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Controlunit;
use App\PhysicalSensor;
use App\Terrarium;
use Illuminate\Http\Request;

use App\Http\Requests;

class PhysicalSensorController extends Controller
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
        return view('physical_sensors.index', [
            'physical_sensors' => PhysicalSensor::get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('physical_sensors.create');
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
        $ps = PhysicalSensor::find($id);
        if (is_null($ps)) {
            return view('errors.404');
        }

        return view('physical_sensors.show', [
            'physical_sensor' => $ps
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
        $physical_sensor = PhysicalSensor::find($id);

        if (is_null($physical_sensor)) {
            return view('errors.404');
        }

        $controlunit = Controlunit::all();
        $terraria = Terrarium::all();

        return view('physical_sensors.edit', [
            'physical_sensor'   => $physical_sensor,
            'controlunits'      => $controlunit,
            'terraria'          => $terraria
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
        $physical_sensor = PhysicalSensor::find($id);

        if (is_null($physical_sensor)) {
            return view('errors.404');
        }

        return view('physical_sensors.delete', [
            'physical_sensor'     => $physical_sensor
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
