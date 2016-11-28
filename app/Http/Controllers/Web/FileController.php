<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\File;
use Illuminate\Http\Request;

use App\Http\Requests;

/**
 * Class FileController
 * @package App\Http\Controllers\Web
 */
class FileController extends Controller
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
        return view('files.index', [
            'files' => File::get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $belongTo_Options = [];
        foreach (File::belongTo_Types() as $t) {
            $belongTo_Options[$t] = ('App\\' . $t)::get();
        }

        return view('files.create', [
            'belongTo_Options' => $belongTo_Options,
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
        $file = File::find($id);
        if (is_null($file)) {
            return view('errors.404');
        }

        return view('files.show', [
            'file' => $file
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
        $file = File::find($id);

        if (is_null($file)) {
            return view('errors.404');
        }

        return view('files.edit', [
            'file'    => $file
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
        $file = File::find($id);

        if (is_null($file)) {
            return view('errors.404');
        }

        return view('files.delete', [
            'file'     => $file
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

    /**
     * @param $id
     * @return mixed
     */
    public function download($id)
    {
        $file = File::find($id);
        if (is_null($file)) {
            return view('errors.404');
        }

        return response()->file($file->path_internal());
    }
}
