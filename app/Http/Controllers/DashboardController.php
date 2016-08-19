<?php

namespace App\Http\Controllers;

use App\ActionSchedule;
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

        return view('dashboard', [
            'terraria' => $terraria_critical
        ]);

    }

}
