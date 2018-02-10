<?php

namespace App\Http\Controllers\Api;

use App\Animal;
use App\Controlunit;
use App\Event;
use App\Http\Transformers\ActionSequenceIntentionTransformer;
use App\Http\Transformers\ActionSequenceScheduleTransformer;
use App\Http\Transformers\ActionSequenceTriggerTransformer;
use App\Http\Transformers\AnimalFeedingSchedulePropertyTransformer;
use App\Http\Transformers\AnimalWeighingSchedulePropertyTransformer;
use App\Http\Transformers\ControlunitTransformer;
use App\Http\Transformers\PhysicalSensorTransformer;
use App\Http\Transformers\SuggestionEventTransformer;
use App\Http\Transformers\TerrariumTransformer;
use App\PhysicalSensor;
use App\Property;
use App\Repositories\GenericRepository;
use App\System;
use App\Terrarium;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Class DashboardController
 * @package App\Http\Controllers\Api
 */
class DashboardController extends ApiController
{
    /**
     * FileController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if (Gate::denies('api-list')) {
            return $this->respondUnauthorized();
        }

        $controlunits_critical = new Collection();
        foreach (Controlunit::orderBy('name')->get() as $controlunit) {
            if (!$controlunit->heartbeatOk() && $controlunit->active()) {
                $controlunits_critical->push($controlunit);
            }
        }
        $controlunits_critical = (new ControlunitTransformer())->transformCollection($controlunits_critical->toArray());

        $physical_sensors_critical = new Collection();
        foreach (PhysicalSensor::orderBy('name')->get() as $physical_sensor) {
            if (!$physical_sensor->heartbeatOk() &&
                !is_null($physical_sensor->controlunit) &&
                $physical_sensor->active() &&
                $physical_sensor->controlunit->active()) {
                $physical_sensors_critical->push($physical_sensor);
            }
        }
        $physical_sensors_critical = (new PhysicalSensorTransformer())->transformCollection($physical_sensors_critical->toArray());

        $terraria_ok = (new TerrariumTransformer())->transformCollection(Terrarium::where('humidity_critical', false)
            ->where('temperature_critical', false)->get()->toArray());

        $terraria_critical = (new TerrariumTransformer())->transformCollection(Terrarium::where(function($query) {
            $query->where('humidity_critical', true)
                ->orWhere('temperature_critical', true);
        })->orderBy('name')->get()->toArray());


        $feeding_schedules = [
            'due' => [],
            'overdue' => []
        ];

        foreach (Animal::whereNull('death_date')->orderBy('display_name')->get() as $animal) {
            $feeding_schedules_temp = $animal->getDueFeedingSchedules();
            foreach ($feeding_schedules_temp as $type=>$schedules) {
                foreach ($schedules as $schedule) {
                    $feeding_schedules[$type][] = (new AnimalFeedingSchedulePropertyTransformer())->transform($schedule->toArray());
                }
            }
        }


        $weighing_schedules = [
            'due' => [],
            'overdue' => []
        ];

        foreach (Animal::whereNull('death_date')->orderBy('display_name')->get() as $animal) {
            $weighing_schedules_temp = $animal->getDueWeighingSchedules();
            foreach ($weighing_schedules_temp as $type=>$schedules) {
                foreach ($schedules as $index=>$schedule) {
                    $weighing_schedules[$type][] = (new AnimalWeighingSchedulePropertyTransformer())->transform($schedule->toArray());
                }
            }
        }


        $action_sequence_schedules = [
            'due' => [],
            'overdue' => [],
            'running' => []
        ];

        $action_sequence_triggers = [
            'running' => [],
            'should_be_running' => []
        ];

        $action_sequence_intentions = [
            'running' => [],
            'should_be_running' => []
        ];

        foreach (Terrarium::orderBy('name')->get() as $terrarium) {
            foreach ($terrarium->action_sequences as $as) {
                foreach ($as->schedules()->with('sequence')->get() as $ass) {
                    if ($ass->willRunToday() && !$ass->isOverdue() && $ass->startsToday()->diffInHours(Carbon::now()) < 3) {
                        $action_sequence_schedules['due'][] = (new ActionSequenceScheduleTransformer())->transform($ass->toArray());
                    }
                    elseif ($ass->isOverdue()) {
                        $action_sequence_schedules['overdue'][] = (new ActionSequenceScheduleTransformer())->transform($ass->toArray());
                    }
                    elseif ($ass->running()) {
                        $action_sequence_schedules['running'][] = (new ActionSequenceScheduleTransformer())->transform($ass->toArray());
                    }
                }
            }

            foreach ($terrarium->action_sequences as $as) {
                foreach ($as->triggers()->with('sequence')->get() as $ast) {
                    if ($ast->running()) {
                        $action_sequence_triggers['running'][] = (new ActionSequenceTriggerTransformer())->transform($ast->toArray());
                    }
                }
            }

            foreach ($terrarium->action_sequences as $as) {
                foreach ($as->intentions()->with('sequence')->get() as $asi) {
                    if ($asi->running()) {
                        $action_sequence_intentions['running'][] = (new ActionSequenceIntentionTransformer())->transform($asi->toArray());
                    }
                    elseif ($asi->shouldBeRunning()) {
                        $action_sequence_intentions['should_be_running'][] = (new ActionSequenceIntentionTransformer())->transform($asi->toArray());
                    }
                }
            }
        }

        $suggestions = [];
        foreach (Event::orderBy('name')->where('type', 'Suggestion')->get() as $suggestion) {

            if (is_null($suggestion->property('ReadFlag'))) {
                $belongsTo = $suggestion->belongsTo_object();
                if (is_null($belongsTo)) {
                    continue;
                }
                $belongsTo = (new GenericRepository($belongsTo))->show();
                $suggestion->belongsTo_object = is_null($belongsTo) ? null : $belongsTo->toArray();
                $violation_type = $suggestion->properties()->where('name', 'violation_type')->get()->first();
                $suggestion->violation_type = is_null($violation_type) ? 'UNKNOWN' : $violation_type->value;
                $suggestions[] = (new SuggestionEventTransformer())->transform($suggestion->toArray());

            }

        }

        return $this->respondWithData([
            'controlunits' => [
                'critical' => $controlunits_critical
            ],
            'physical_sensors' => [
                'critical' => $physical_sensors_critical
            ],
            'terraria' => [
                'ok' => $terraria_ok,
                'critical' => $terraria_critical
            ],
            'animal_feeding_schedules' => $feeding_schedules,
            'animal_weighing_schedules' => $weighing_schedules,
            'action_sequence_schedules' => $action_sequence_schedules,
            'action_sequence_triggers' => $action_sequence_triggers,
            'action_sequence_intentions' => $action_sequence_intentions,
            'suggestions' => $suggestions
        ]);
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
    public function show(Request $request, $id)
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

    /**
     *
     */
    public function system_status()
    {
        return $this->respondWithData(System::status());
    }
}
