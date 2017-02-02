<?php

namespace App\Http\Controllers\Web;

use App\ActionSequence;
use App\Http\Controllers\Controller;
use App\ActionSequenceTrigger;
use App\LogicalSensor;
use Illuminate\Http\Request;

use App\Http\Requests;

/**
 * Class ActionSequenceTriggerController
 * @package App\Http\Controllers\Web
 */
class ActionSequenceTriggerController extends Controller
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
        return view('action_sequence_triggers.index', [
            'action_sequence_triggers' => ActionSequenceTrigger::get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $ls = null;
        if (isset($request->input('preset')['action_sequence'])) {
            $as = ActionSequence::find($request->input('preset')['action_sequence']);
            if (!is_null($as)) {
                $ls = $as->terrarium->logical_sensors;
            }
        }

        if (is_null($ls)) {
            $ls = LogicalSensor::get();
        }

        return view('action_sequence_triggers.create', [
            'action_sequences'  => ActionSequence::all(),
            'logical_sensors'   => $ls,
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
        $action_sequence_trigger = ActionSequenceTrigger::find($id);
        if (is_null($action_sequence_trigger)) {
            return view('errors.404');
        }

        return view('action_sequence_triggers.show', [
            'action_sequence_trigger' => $action_sequence_trigger
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
        $action_sequence_trigger = ActionSequenceTrigger::find($id);
        $sequences = ActionSequence::all();

        if (is_null($action_sequence_trigger)) {
            return view('errors.404');
        }

        return view('action_sequence_triggers.edit', [
            'action_sequence_trigger'   => $action_sequence_trigger,
            'action_sequences'          => $sequences,
            'logical_sensors'           =>  $action_sequence_trigger->sequence->terrarium->logical_sensors
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
        $action_sequence_trigger = ActionSequenceTrigger::find($id);

        if (is_null($action_sequence_trigger)) {
            return view('errors.404');
        }

        return view('action_sequence_triggers.delete', [
            'action_sequence_trigger'     => $action_sequence_trigger
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
