<?php

namespace App\Http\Controllers\Web;

use App\ActionSequence;
use App\ActionSequenceSchedule;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * Class ActionSequenceScheduleController
 * @package App\Http\Controllers\Web
 */
class ActionSequenceScheduleController extends Controller
{

    /**
     * ApiController constructor.
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
        return view('action_sequence_schedules.index', [
            'action_sequence_schedules' => ActionSequenceSchedule::get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $sequences = ActionSequence::all();

        return view('action_sequence_schedules.create', [
            'action_sequences'        => $sequences,
            'preset' => $request->input('preset')
        ]);
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
        $action_sequence_schedule = ActionSequenceSchedule::find($id);
        if (is_null($action_sequence_schedule)) {
            return response()->view('errors.404', [], 404);
        }

        return view('action_sequence_schedules.show', [
            'action_sequence_schedule' => $action_sequence_schedule
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $action_sequence_schedule = ActionSequenceSchedule::find($id);
        $sequences = ActionSequence::all();

        if (is_null($action_sequence_schedule)) {
            return response()->view('errors.404', [], 404);
        }

        return view('action_sequence_schedules.edit', [
            'action_sequence_schedule'     => $action_sequence_schedule,
            'action_sequences'        => $sequences
        ]);
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
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        $action_sequence_schedule = ActionSequenceSchedule::find($id);

        if (is_null($action_sequence_schedule)) {
            return response()->view('errors.404', [], 404);
        }

        return view('action_sequence_schedules.delete', [
            'action_sequence_schedule'     => $action_sequence_schedule
        ]);
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
