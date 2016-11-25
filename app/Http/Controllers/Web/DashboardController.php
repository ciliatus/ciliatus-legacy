<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\ActionSequenceSchedule;
use App\Terrarium;
use Illuminate\Http\Request;

use App\Http\Requests;

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
