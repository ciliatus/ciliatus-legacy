<?php

namespace App\Http\Controllers\Web;

use App\Animal;
use App\Controlunit;
use App\CustomComponent;
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
     * @return void
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return void
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $id
     * @return void
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param string $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return void
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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
            'custom_components' => CustomComponent::get()
        ]);
    }
}
