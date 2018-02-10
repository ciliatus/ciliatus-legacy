<?php

namespace App\Http\Controllers\Web;

use App\File;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!$request->has('preset')) {
            $belongTo_Options = [];
            foreach (File::belongTo_Types() as $t) {
                $belongTo_Options[$t] = ('App\\' . $t)::get();
            }
        }
        else {
            $belongTo_Options = [];
        }

        return view('files.create', [
            'preset_defined' => $request->has('preset'),
            'belongTo_Options' => $belongTo_Options,
            'preset' => $request->input('preset')
        ]);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $file = File::find($id);
        if (is_null($file)) {
            return response()->view('errors.404', [], 404);
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
            return response()->view('errors.404', [], 404);
        }

        return view('files.edit', [
            'file'    => $file
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return void
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
            return response()->view('errors.404', [], 404);
        }

        return view('files.delete', [
            'file'     => $file
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return void
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @param $id
     * @param $display_name for pretty urls
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function download($id, $display_name = null)
    {
        $file = File::find($id);
        if (is_null($file)) {
            return response()->view('errors.404', [], 404);
        }

        return response()->file($file->path_internal());
    }

    /**
     * @param $type
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function associate($type, $id)
    {
        $source_class = 'App\\' . $type;
        $source = $source_class::find($id);
        if (is_null($source)) {
            return response()->view('errors.404', [], 404);
        }

        return view('files.associate', [
            'type' => $type,
            'source' => $source,
            'target_type_url_name' => 'files'
        ]);
    }

    /**
     * @param Request $request
     * @param $type
     * @param $id
     * @param $file_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function associate_delete(Request $request, $type, $id, $file_id)
    {
        $source_class = 'App\\' . $type;
        $source = $source_class::find($id);
        if (is_null($source)) {
            return response()->view('errors.404', [], 404);
        }

        $file = File::find($file_id);
        if (is_null($file)) {
            return response()->view('errors.404', [], 404);
        }

        return view('files.associate_delete', [
            'file'      => $file->enrich(),
            'source'    => $source->enrich()
        ]);
    }

    /**
     * @param Request $request
     * @param $type
     * @param $id
     * @param $file_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function set_background(Request $request, $type, $id, $file_id)
    {
        $source_class = 'App\\' . $type;
        $source = $source_class::find($id);
        if (is_null($source)) {
            return response()->view('errors.404', [], 404);
        }

        $file = File::find($file_id);
        if (is_null($file)) {
            return response()->view('errors.404', [], 404);
        }

        return view('files.set_background', [
            'file'      => $file->enrich(),
            'source'    => $source->enrich()
        ]);
    }
}
