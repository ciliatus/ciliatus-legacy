<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\ActionSequence;
use App\ActionSequenceSchedule;
use App\Terrarium;
use Illuminate\Http\Request;

use App\Http\Requests;

/**
 * Class ActionSequenceController
 * @package App\Http\Controllers\Web
 */
class ActionSequenceController extends Controller
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
        return view('action_sequences.index', [
            'action_sequences' => ActionSequence::get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $schedules = ActionSequenceSchedule::all();

        return view('action_sequences.create', [
            'schedules'        => $schedules
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
        $action_sequence = ActionSequence::find($id);
        if (is_null($action_sequence)) {
            return view('errors.404');
        }

        return view('action_sequences.show', [
            'action_sequence' => $action_sequence
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
        $action_sequence = ActionSequence::find($id);

        if (is_null($action_sequence)) {
            return view('errors.404');
        }

        return view('action_sequences.delete', [
            'action_sequence'     => $action_sequence
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