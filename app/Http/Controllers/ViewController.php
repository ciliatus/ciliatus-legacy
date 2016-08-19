<?php

namespace App\Http\Controllers;

use App\Action;
use App\ActionSequence;
use App\ActionSequenceSchedule;
use App\Animal;
use App\Controlunit;
use App\Log;
use App\LogicalSensor;
use App\LogicalSensorThreshold;
use App\PhysicalSensor;
use App\Pump;
use App\Terrarium;
use App\Token;
use App\User;
use App\Valve;
use App\File;
use Auth;

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

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function animalsShow($id)
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

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pumpsShow($id)
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

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function valvesShow($id)
    {
        $valve = Valve::find($id);
        if (is_null($valve)) {
            return view('errors.404');
        }

        return view('valves.show', [
            'valve' => $valve
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

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function controlunitsShow($id)
    {
        $cu = Controlunit::find($id);
        if (is_null($cu)) {
            return view('errors.404');
        }

        return view('controlunits.show', [
            'controlunit' => $cu
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

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function physical_sensorsShow($id)
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

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function logical_sensorsShow($id)
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
        $logical_sensors = LogicalSensor::all();

        return view('logical_sensors.edit', [
            'logical_sensor'    => $logical_sensor,
            'physical_sensors'  => $physical_sensors,
            'logical_sensors'   =>  $logical_sensors,
        ]);
    }


    /*
     * File
     */
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function files()
    {
        return view('files.index', [
            'files' => File::get()
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function filesShow($id)
    {
        $file = File::find($id);
        if (is_null($file)) {
            return view('errors.404');
        }

        return view('files.show', [
            'file' => $file
        ]);
    }


    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function filesDownload($id)
    {
        $file = File::find($id);
        if (is_null($file)) {
            return view('errors.404');
        }

        return response()->file($file->path());
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function filesCreate()
    {
        return view('files.create');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function filesDelete($id)
    {
        $file = File::find($id);

        if (is_null($file)) {
            return view('errors.404');
        }

        return view('files.delete', [
            'file'     => $file
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function filesEdit($id)
    {
        $file = File::find($id);

        if (is_null($file)) {
            return view('errors.404');
        }

        $physical_sensors = PhysicalSensor::all();

        return view('files.edit', [
            'file'    => $file,
            'physical_sensors'  => $physical_sensors,
        ]);
    }

    /*
     * LogicalSensorThreshold
     */
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function logical_sensor_thresholds()
    {
        return view('logical_sensor_thresholds.index', [
            'logical_sensor_thresholds' => LogicalSensorThreshold::orderBy('created_at', 'desc')->get()
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function logical_sensor_thresholdsShow($id)
    {
        $logical_sensor_threshold = LogicalSensorThreshold::find($id);
        if (is_null($logical_sensor_threshold)) {
            return view('errors.404');
        }

        return view('logical_sensor_thresholds.show', [
            'logical_sensor_threshold' => $logical_sensor_threshold
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function logical_sensor_thresholdsCreate()
    {
        $logical_sensors = LogicalSensor::all();

        return view('logical_sensor_thresholds.create', [
            'logical_sensors'        => $logical_sensors
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function logical_sensor_thresholdsDelete($id)
    {
        $logical_sensor_threshold = LogicalSensorThreshold::find($id);

        if (is_null($logical_sensor_threshold)) {
            return view('errors.404');
        }

        return view('logical_sensor_thresholds.delete', [
            'logical_sensor_threshold'     => $logical_sensor_threshold
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function logical_sensor_thresholdsEdit($id)
    {
        $logical_sensor_threshold = LogicalSensorThreshold::find($id);

        if (is_null($logical_sensor_threshold)) {
            return view('errors.404');
        }

        $logical_sensors = LogicalSensor::all();

        return view('logical_sensor_thresholds.edit', [
            'logical_sensor_threshold'     => $logical_sensor_threshold,
            'logical_sensors'        => $logical_sensors
        ]);
    }


    /*
     * Log
     */
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function logs()
    {
        return view('logs.index', [
            'logs' => Log::orderBy('created_at', 'desc')->get()
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function logsShow($id)
    {
        $log = Log::find($id);
        if (is_null($log)) {
            return view('errors.404');
        }

        return view('logs.show', [
            'log' => $log
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function logsCreate()
    {
        return view('logs.create');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function logsDelete($id)
    {
        $log = Log::find($id);

        if (is_null($log)) {
            return view('errors.404');
        }

        return view('logs.delete', [
            'log'     => $log
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function logsEdit($id)
    {
        $log = Log::find($id);

        if (is_null($log)) {
            return view('errors.404');
        }

        $terraria = Terrarium::all();

        return view('logs.edit', [
            'log'     => $log,
            'terraria'        => $terraria
        ]);
    }


    /*
     * Log
     */
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function users()
    {
        return view('users.index', [
            'users' => User::orderBy('name')->get()
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function usersShow($id)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return view('errors.404');
        }

        return view('users.show', [
            'user' => $user
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function usersCreate()
    {
        return view('users.create');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function usersDelete($id)
    {
        $user = User::find($id);

        if (is_null($user)) {
            return view('errors.404');
        }

        return view('users.delete', [
            'user'     => $user
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function usersEdit($id)
    {
        $user = User::find($id);

        if (is_null($user)) {
            return view('errors.404');
        }


        return view('users.edit', [
            'user'     => $user
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function usersSetupTelegram()
    {
        $user = Auth::user();

        $user->deleteSetting('notifications_telegram_chat_id');
        $user->setSetting('notifications_telegram_verification_code', Token::generate(6));

        return view('users.setup_telegram', [
            'user'  =>  $user,
            'token' =>  $user->setting('notifications_telegram_verification_code')
        ]);
    }


    /*
     * Actions
     */
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function actions()
    {
        return view('actions.index', [
            'actions' => Action::get()
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function actionsShow($id)
    {
        $action = Action::find($id);
        if (is_null($action)) {
            return view('errors.404');
        }

        return view('actions.show', [
            'action' => $action
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function actionsCreate()
    {
        $sequences = ActionSequence::all();

        return view('actions.create', [
            'sequences'        => $sequences
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function actionsDelete($id)
    {
        $action = Action::find($id);

        if (is_null($action)) {
            return view('errors.404');
        }

        return view('actions.delete', [
            'action'     => $action
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function actionsEdit($id)
    {
        $action = Action::find($id);

        if (is_null($action)) {
            return view('errors.404');
        }

        $sequences = ActionSequence::all();

        return view('actions.edit', [
            'action'     => $action,
            'sequences'        => $sequences
        ]);
    }


    /*
     * ActionSequences
     */
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function action_sequences()
    {
        return view('action_sequences.index', [
            'action_sequences' => ActionSequence::get()
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function action_sequencesShow($id)
    {
        $action_sequence = ActionSequence::find($id);
        if (is_null($action_sequence)) {
            return view('errors.404');
        }

        return view('action_sequences.show', [
            'action_sequence' => $action_sequence
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function action_sequencesCreate()
    {
        $schedules = ActionSequenceSchedule::all();

        return view('action_sequences.create', [
            'schedules'        => $schedules
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function action_sequencesDelete($id)
    {
        $action_sequence = ActionSequence::find($id);

        if (is_null($action_sequence)) {
            return view('errors.404');
        }

        return view('action_sequences.delete', [
            'action_sequence'     => $action_sequence
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function action_sequencesEdit($id)
    {
        $action_sequence = ActionSequence::find($id);
        $terraria = Terrarium::get();

        if (is_null($action_sequence)) {
            return view('errors.404');
        }

        return view('action_sequences.edit', [
            'action_sequence'  => $action_sequence,
            'terraria'         => $terraria
        ]);
    }

    /*
     * ActionSequenceSchedules
     */
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function action_sequence_schedules()
    {
        return view('action_sequence_schedules.index', [
            'action_sequence_schedules' => ActionSequenceSchedule::get()
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function action_sequence_schedulesShow($id)
    {
        $action_sequence_schedule = ActionSequenceSchedule::find($id);
        if (is_null($action_sequence_schedule)) {
            return view('errors.404');
        }

        return view('action_sequence_schedules.show', [
            'action_sequence_schedule' => $action_sequence_schedule
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function action_sequence_schedulesCreate()
    {
        $sequences = ActionSequence::all();

        return view('action_sequence_schedules.create', [
            'sequences'        => $sequences
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function action_sequence_schedulesDelete($id)
    {
        $action_sequence_schedule = ActionSequenceSchedule::find($id);

        if (is_null($action_sequence_schedule)) {
            return view('errors.404');
        }

        return view('action_sequence_schedules.delete', [
            'action_sequence_schedule'     => $action_sequence_schedule
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function action_sequence_schedulesEdit($id)
    {
        $action_sequence_schedule = ActionSequenceSchedule::find($id);

        if (is_null($action_sequence_schedule)) {
            return view('errors.404');
        }

        return view('action_sequence_schedules.edit', [
            'action_sequence_schedule'     => $action_sequence_schedule
        ]);
    }


}
