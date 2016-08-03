<?php

namespace App\Http\Controllers;

use App\Animal;
use App\Controlunit;
use App\LogicalSensor;
use App\PhysicalSensor;
use App\Pump;
use App\Terrarium;
use App\Valve;

/**
 * Class ApiController
 * @package App\Http\Controllers
 */
class ViewController extends Controller
{

    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('dashboard', [
            'terraria' => Terrarium::get()
        ]);
    }
    
    /*
     * Terraria
     */
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function terraria()
    {
        return view('terraria.index', [
            'terraria' => Terrarium::get()
        ]);
    }

    public function terrariaShow($id)
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function terrariaCreate()
    {
        return view('terraria.create');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function terrariaDelete($id)
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
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function terrariaEdit($id)
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

    /*
     * Animal
     */
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function animals()
    {
        return view('animals.index', [
            'animals' => Animal::orderBy('death_date')->get()
        ]);
    }

    public function animalsShow($id)
    {
        $animal = Animal::find($id);
        if (is_null($animal)) {
            return view('errors.404');
        }

        return view('animals.index', [
            'animals' => [$animal]
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function animalsCreate()
    {
        return view('animals.create');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function animalsDelete($id)
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
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function animalsEdit($id)
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

    /*
     * Pump
     */
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pumps()
    {
        return view('pumps.index', [
            'pumps' => Pump::get()
        ]);
    }

    public function pumpsShow($id)
    {
        $pump = Pump::find($id);
        if (is_null($pump)) {
            return view('errors.404');
        }

        return view('pumps.index', [
            'pumps' => [$pump]
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pumpsCreate()
    {
        return view('pumps.create');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pumpsDelete($id)
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
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pumpsEdit($id)
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


    /*
     * Valve
     */
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function valves()
    {
        return view('valves.index', [
            'valves' => Valve::get()
        ]);
    }

    public function valvesShow($id)
    {
        $valve = Valve::find($id);
        if (is_null($valve)) {
            return view('errors.404');
        }

        return view('valves.index', [
            'valves' => [$valve]
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function valvesCreate()
    {
        return view('valves.create');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function valvesDelete($id)
    {
        $valve = Valve::find($id);

        if (is_null($valve)) {
            return view('errors.404');
        }

        return view('valves.delete', [
            'valve'     => $valve
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function valvesEdit($id)
    {
        $valve = Valve::find($id);

        if (is_null($valve)) {
            return view('errors.404');
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


    /*
     * Controlunit
     */
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function controlunits()
    {
        return view('controlunits.index', [
            'controlunits' => Controlunit::get()
        ]);
    }

    public function controlunitsShow($id)
    {
        $cu = Controlunit::find($id);
        if (is_null($cu)) {
            return view('errors.404');
        }

        return view('controlunits.index', [
            'controlunits' => [$cu]
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function controlunitsCreate()
    {
        return view('controlunits.create');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function controlunitsDelete($id)
    {
        $controlunit = Controlunit::find($id);

        if (is_null($controlunit)) {
            return view('errors.404');
        }

        return view('controlunits.delete', [
            'controlunit'     => $controlunit
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function controlunitsEdit($id)
    {
        $controlunit = Controlunit::find($id);

        if (is_null($controlunit)) {
            return view('errors.404');
        }

        return view('controlunits.edit', [
            'controlunit'     => $controlunit
        ]);
    }


    /*
     * PhysicalSensor
     */
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function physical_sensors()
    {
        return view('physical_sensors.index', [
            'physical_sensors' => PhysicalSensor::get()
        ]);
    }

    public function physical_sensorsShow($id)
    {
        $ps = PhysicalSensor::find($id);
        if (is_null($ps)) {
            return view('errors.404');
        }

        return view('physical_sensors.index', [
            'physical_sensors' => [$ps]
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function physical_sensorsCreate()
    {
        return view('physical_sensors.create');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function physical_sensorsDelete($id)
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
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function physical_sensorsEdit($id)
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


    /*
     * LogicalSensor
     */
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function logical_sensors()
    {
        return view('logical_sensors.index', [
            'logical_sensors' => LogicalSensor::get()
        ]);
    }

    public function logical_sensorsShow($id)
    {
        $ls = LogicalSensor::find($id);
        if (is_null($ls)) {
            return view('errors.404');
        }

        return view('logical_sensors.index', [
            'logical_sensors' => [$ls]
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function logical_sensorsCreate()
    {
        return view('logical_sensors.create');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function logical_sensorsDelete($id)
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
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function logical_sensorsEdit($id)
    {
        $logical_sensor = LogicalSensor::find($id);

        if (is_null($logical_sensor)) {
            return view('errors.404');
        }

        $physical_sensors = PhysicalSensor::all();

        return view('logical_sensors.edit', [
            'logical_sensor'    => $logical_sensor,
            'physical_sensors'  => $physical_sensors,
        ]);
    }

}
