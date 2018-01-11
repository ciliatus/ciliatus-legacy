<?php

namespace App\Http\Controllers\Web;

use App\ActionSequence;
use App\Http\Controllers\Controller;
use App\ActionSequenceIntention;
use App\LogicalSensor;
use App\Sensorreading;
use Illuminate\Http\Request;

use App\Http\Requests;

/**
 * Class ActionSequenceIntentionController
 * @package App\Http\Controllers\Web
 */
class ActionSequenceIntentionController extends Controller
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
        return view('action_sequence_intentions.index', [
            'action_sequence_intentions' => ActionSequenceIntention::get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $sensorreading_types = LogicalSensor::types();

        return view('action_sequence_intentions.create', [
            'action_sequences'  => ActionSequence::all(),
            'sensorreading_types'   => $sensorreading_types,
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
        $action_sequence_intention = ActionSequenceIntention::find($id);
        if (is_null($action_sequence_intention)) {
            return response()->view('errors.404', [], 404);
        }

        return view('action_sequence_intentions.show', [
            'action_sequence_intention' => $action_sequence_intention
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
        $action_sequence_intention = ActionSequenceIntention::find($id);
        $sequences = ActionSequence::all();

        if (is_null($action_sequence_intention)) {
            return response()->view('errors.404', [], 404);
        }

        $sensorreading_types = LogicalSensor::types();

        return view('action_sequence_intentions.edit', [
            'action_sequence_intention' => $action_sequence_intention,
            'action_sequences'          => $sequences,
            'sensorreading_types'       => $sensorreading_types
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
        $action_sequence_intention = ActionSequenceIntention::find($id);

        if (is_null($action_sequence_intention)) {
            return response()->view('errors.404', [], 404);
        }

        return view('action_sequence_intentions.delete', [
            'action_sequence_intention'     => $action_sequence_intention
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
