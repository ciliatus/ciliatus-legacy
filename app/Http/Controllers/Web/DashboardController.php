<?php

namespace App\Http\Controllers\Web;

use App\Animal;
use App\Controlunit;
use App\GenericComponent;
use App\Http\Controllers\Controller;
use App\LogicalSensor;
use App\PhysicalSensor;
use App\Pump;
use App\Terrarium;
use App\Valve;
use Illuminate\Http\Request;

/**
 * Class DashboardController
 * @package App\Http\Controllers\Web
 */
class DashboardController extends Controller
{

    /**
     * DashboardController constructor.
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
        return view('dashboard');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function map()
    {
        return view('map', [
            'terraria' => Terrarium::get(),
            'animals' => Animal::get(),
            'controlunits' => Controlunit::get(),
            'physical_sensors' => PhysicalSensor::get(),
            'logical_sensors' => LogicalSensor::get(),
            'valves' => Valve::get(),
            'pumps' => Pump::get(),
            'generic_components' => GenericComponent::get()
        ]);
    }
}
