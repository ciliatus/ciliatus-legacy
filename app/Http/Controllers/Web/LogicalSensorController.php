<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\LogicalSensor;
use App\PhysicalSensor;
use Illuminate\Http\Request;

use App\Http\Requests;

class LogicalSensorController extends Controller
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
        return view('logical_sensors.index', [
            'logical_sensors' => LogicalSensor::get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $physical_sensors = PhysicalSensor::all();

        return view('logical_sensors.create', [
            'preset' => $request->input('preset'),
            'physical_sensors'  => $physical_sensors
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
        $ls = LogicalSensor::find($id);
        if (is_null($ls)) {
            return view('errors.404');
        }

        return view('logical_sensors.show', [
            'logical_sensor' => $ls
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
        $logical_sensor = LogicalSensor::find($id);

        if (is_null($logical_sensor)) {
            return view('errors.404');
        }

        $physical_sensors = PhysicalSensor::all();
        $logical_sensors = LogicalSensor::all();
        $copy_target_ls = LogicalSensor::where('id', '!=', $id)->where('type', $logical_sensor->type)->get();

        return view('logical_sensors.edit', [
            'logical_sensor'    => $logical_sensor,
            'physical_sensors'  => $physical_sensors,
            'logical_sensors'   =>  $logical_sensors,
            'copy_target_ls' => $copy_target_ls
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
        $logical_sensor = LogicalSensor::find($id);

        if (is_null($logical_sensor)) {
            return view('errors.404');
        }

        return view('logical_sensors.delete', [
            'logical_sensor'     => $logical_sensor
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
