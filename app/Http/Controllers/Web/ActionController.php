<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Action;
use App\ActionSequence;
use Illuminate\Http\Request;

use App\Http\Requests;

/**
 * Class ActionController
 * @package App\Http\Controllers\Web
 */
class ActionController extends Controller
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
        return view('actions.index', [
            'actions' => Action::get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sequences = ActionSequence::all();

        return view('actions.create', [
            'sequences'        => $sequences
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
        $action = Action::find($id);
        if (is_null($action)) {
            return view('errors.404');
        }

        return view('actions.show', [
            'action' => $action
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
        $action = Action::find($id);

        if (is_null($action)) {
            return view('errors.404');
        }

        $sequences = ActionSequence::all();

        return view('actions.edit', [
            'action'     => $action,
            'sequences'  => $sequences
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
        $action = Action::find($id);

        if (is_null($action)) {
            return view('errors.404');
        }

        return view('actions.delete', [
            'action'     => $action
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
