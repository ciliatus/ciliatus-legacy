<?php

namespace App\Http\Controllers\Api;

use App\Animal;
use App\Http\Transformers\ActionSequenceScheduleTransformer;
use App\Http\Transformers\AnimalFeedingScheduleTransformer;
use App\Http\Transformers\AnimalWeighingScheduleTransformer;
use App\Http\Transformers\TerrariumTransformer;
use App\Repositories\AnimalFeedingScheduleRepository;
use App\Repositories\AnimalWeighingScheduleRepository;
use Gate;
use App\Terrarium;
use Illuminate\Http\Request;

use App\Http\Requests;

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
    public function index()
    {
        if (Gate::denies('api-list')) {
            return $this->respondUnauthorized();
        }

        $terraria_ok = (new TerrariumTransformer())->transformCollection(Terrarium::where('humidity_critical', false)
            ->where('temperature_critical', false)->get()->toArray());

        $terraria_critical = (new TerrariumTransformer())->transformCollection(Terrarium::where(function($query) {
            $query->where('humidity_critical', true)
                ->orWhere('temperature_critical', true);
        })->get()->toArray());


        $feeding_schedules = [
            'due' => [],
            'overdue' => []
        ];

        foreach (Animal::get() as $animal) {
            foreach ($animal->feeding_schedules as $afs) {
                $afs = (new AnimalFeedingScheduleRepository($afs))->show();
                if ($afs->next_feeding_at_diff == 0) {
                    $feeding_schedules['due'][] = (new AnimalFeedingScheduleTransformer())->transform($afs->toArray());
                } elseif ($afs->next_feeding_at_diff < 0) {
                    $feeding_schedules['overdue'][] = (new AnimalFeedingScheduleTransformer())->transform($afs->toArray());
                }
            }
        }


        $weighing_schedules = [
            'due' => [],
            'overdue' => []
        ];

        foreach (Animal::get() as $animal) {
            foreach ($animal->weighing_schedules as $afs) {
                $afs = (new AnimalWeighingScheduleRepository($afs))->show();
                if ($afs->next_weighing_at_diff == 0) {
                    $weighing_schedules['due'][] = (new AnimalWeighingScheduleTransformer())->transform($afs->toArray());
                } elseif ($afs->next_weighing_at_diff < 0) {
                    $weighing_schedules['overdue'][] = (new AnimalWeighingScheduleTransformer())->transform($afs->toArray());
                }
            }
        }



        $action_sequence_schedules = [
            'due' => [],
            'overdue' => [],
            'running' => []
        ];

        foreach (Terrarium::get() as $terrarium) {
            foreach ($terrarium->action_sequences as $as) {
                foreach ($as->schedules()->with('sequence')->get() as $ass) {
                    if ($ass->will_run_today() && !$ass->is_overdue()) {
                        $action_sequence_schedules['due'][] = (new ActionSequenceScheduleTransformer())->transform($ass->toArray());
                    }
                    elseif ($ass->is_overdue()) {
                        $action_sequence_schedules['overdue'][] = (new ActionSequenceScheduleTransformer())->transform($ass->toArray());
                    }
                    elseif ($ass->running()) {
                        $action_sequence_schedules['running'][] = (new ActionSequenceScheduleTransformer())->transform($ass->toArray());
                    }
                }
            }
        }


        return $this->respondWithData([
            'terraria' => [
                'ok' => $terraria_ok,
                'critical' => $terraria_critical
            ],
            'animal_feeding_schedules' => $feeding_schedules,
            'animal_weighing_schedules' => $weighing_schedules,
            'action_sequence_schedules' => $action_sequence_schedules
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
}
