<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Log;
use Gate;
use Illuminate\Http\Request;

/**
 * Class LogController
 * @package App\Http\Controllers\Web
 */
class LogController extends Controller
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
        if (Gate::denies('admin')) {
            return response()->view('errors.401', [], 401);
        }

        return view('logs.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        
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
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $log = Log::find($id);
        if (is_null($log)) {
            return response()->view('errors.404', [], 404);
        }

        return view('logs.show', [
            'log' => $log
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $id
     * @return void
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param string $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function delete($id)
    {

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
}
