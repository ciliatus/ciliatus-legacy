<?php

namespace App\Http\Controllers;

use App\Action;
use App\ActionSchedule;
use App\ActionSequenceSchedule;
use App\Terrarium;
use Illuminate\Http\Request;

use App\Http\Requests;

class DashboardController extends Controller
{

    /**
     * DashboardController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Request $request)
    {

        $terraria_critical = [];
        foreach (Terrarium::get() as $t) {
            if (!$t->stateOk()) {
                $terraria_critical[] = $t;
            }
        }

        $ass_will_run_today = [];
        $ass_running = [];
        foreach (ActionSequenceSchedule::all() as $ass) {
            if ($ass->will_run_today()) {
                $ass_will_run_today[] = $ass;
            }
            elseif ($ass->running()) {
                $ass_running[] = $ass;
            }
        }

        return view('dashboard', [
            'terraria' => $terraria_critical,
            'ass_will_run_today' => $ass_will_run_today,
            'ass_running' => $ass_running
        ]);

    }

}
