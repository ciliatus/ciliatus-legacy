<?php

namespace App\Http\Controllers\Api;

use App\ActionSequence;
use App\ActionSequenceIntention;
use App\ActionSequenceSchedule;
use App\ActionSequenceTrigger;
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
use App\SuggestionEvent;
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
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
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
                $controlunits_critical->push($controlunit->enrich());
            }
        }
        $controlunits_critical = (new ControlunitTransformer())->transformCollection($controlunits_critical->toArray());

        $physical_sensors_critical = new Collection();
        foreach (PhysicalSensor::orderBy('name')->get() as $physical_sensor) {
            if (!$physical_sensor->heartbeatOk() &&
                !is_null($physical_sensor->controlunit) &&
                $physical_sensor->active() &&
                $physical_sensor->controlunit->active()) {
                $physical_sensors_critical->push($physical_sensor->enrich());
            }
        }
        $physical_sensors_critical = (new PhysicalSensorTransformer())->transformCollection($physical_sensors_critical->toArray());

        $terraria_ok_count = Terrarium::where('humidity_critical', false)
                                      ->where('temperature_critical', false)
                                      ->count();

        $terraria_critical = [];
        foreach (Terrarium::where(function ($query) {
                    $query->where('humidity_critical', true)
                          ->orWhere('temperature_critical', true);
                })->orderBy('name')
                  ->get() as $terrarium) {
            $terraria_critical[] = $terrarium->enrichAndTransform();
        }

        $feeding_schedules = [];
        foreach (Animal::whereNull('death_date')->orderBy('display_name')->get() as $animal) {
            $feeding_schedules_temp = $animal->getDueFeedingSchedules();
            foreach ($feeding_schedules_temp as $type => $schedules) {
                foreach ($schedules as $schedule) {
                    $feeding_schedules[] = $schedule->enrichAndTransform();
                }
            }
        }

        $weighing_schedules = [];
        foreach (Animal::whereNull('death_date')->orderBy('display_name')->get() as $animal) {
            $weighing_schedules_temp = $animal->getDueWeighingSchedules();
            foreach ($weighing_schedules_temp as $type => $schedules) {
                foreach ($schedules as $index => $schedule) {
                    $weighing_schedules[] = $schedule->enrichAndTransform();
                }
            }
        }

        $action_sequence_schedules = [];
        $action_sequence_triggers = [];
        $action_sequence_intentions = [];

        /**
         * @var ActionSequence $sequence
         */
        foreach (ActionSequence::get() as $sequence) {
            /**
             * @var ActionSequenceSchedule $schedule
             */
            foreach ($sequence->schedules as $schedule) {
                if (($schedule->willRunToday() &&
                   !$schedule->isOverdue() &&
                    $schedule->startsToday()->diffInHours(Carbon::now()) < 3)
                ||
                    $schedule->isOverdue()
                ||
                    $schedule->running()
                ) {
                    $schedule->sequence = $schedule->sequence()->get()->first()->enrich();
                    $action_sequence_schedules[] = $schedule->enrichAndTransform();
                }
            }

            /**
             * @var ActionSequenceTrigger $trigger
             */
            foreach ($sequence->triggers as $trigger) {
                if ($trigger->running()
                ||
                    $trigger->shouldBeStarted()
                ) {
                    $trigger->sequence = $trigger->sequence()->get()->first()->enrich();
                    $action_sequence_triggers[] = $trigger->enrichAndTransform();
                }
            }

            /**
             * @var ActionSequenceIntention $intention
             */
            foreach ($sequence->intentions as $intention) {
                if ($intention->running()
                ||
                    $intention->shouldBeStarted()
                ) {
                    $intention->sequence = $intention->sequence()->get()->first()->enrich();
                    $action_sequence_intentions[] = $intention->enrichAndTransform();
                }
            }
        }

        $suggestions = [];
        foreach (SuggestionEvent::orderBy('name')->get() as $suggestion) {

            if (is_null($suggestion->property('ReadFlag'))) {
                $belongsTo = $suggestion->belongsTo_object();
                if (is_null($belongsTo)) {
                    continue;
                }
                $belongsTo = (new GenericRepository($belongsTo))->show();
                $suggestion->belongsTo_object = is_null($belongsTo) ? null : $belongsTo->toArray();
                $violation_type = $suggestion->properties()->where('name', 'violation_type')->get()->first();
                $suggestion->violation_type = is_null($violation_type) ? 'UNKNOWN' : $violation_type->value;
                $suggestions[] = $suggestion->enrichAndTransform();

            }

        }

        return $this->respondWithData([
            'controlunits'               => $controlunits_critical,
            'physical_sensors'           => $physical_sensors_critical,
            'terraria_ok_count'          => $terraria_ok_count,
            'terraria'                   => $terraria_critical,
            'animal_feeding_schedules'   => $feeding_schedules,
            'animal_weighing_schedules'  => $weighing_schedules,
            'action_sequence_schedules'  => $action_sequence_schedules,
            'action_sequence_triggers'   => $action_sequence_triggers,
            'action_sequence_intentions' => $action_sequence_intentions,
            'suggestions'                => $suggestions
        ]);
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
     * @param Request $request
     * @param string  $id
     * @return void
     */
    public function show(Request $request, $id)
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
     * @param string                    $id
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
     *
     */
    public function system_status()
    {
        return $this->respondWithData(System::status());
    }
}
