<?php

namespace App\Http\Controllers\Web;

use App\Event;
use App\Property;
use Gate;
use Illuminate\Http\Request;

use App\Http\Requests;

/**
 * Class BiographyEntryController
 * @package App\Http\Controllers
 */
class BiographyEntryController extends \App\Http\Controllers\Controller
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
        //
    }


    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        if (Gate::denies('api-write:property')) {
            return view('errors.401');
        }

        $belongTo_Options = [];
        foreach (Property::belongTo_Types('BiographyEntry') as $t) {
            $belongTo_Options[$t] = ('App\\' . $t)::get();
        }

        return view('biographies.entries.create', [
            'belongTo_Options' => $belongTo_Options,
            'preset' => $request->input('preset'),
            'categories' => Property::where('type', 'BiographyEntryCategoryType')->orderBy('name')->get()
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
        if (Gate::denies('api-write:property')) {
            return view('errors.401');
        }

        $entry = Event::find($id);
        if (is_null($entry)) {
            return view('errors.404');
        }

        $category = Property::where('belongsTo_type', 'Event')->where('belongsTo_id', $entry->id)->get()->first();

        $belongTo_Options = [];
        foreach (Property::belongTo_Types('BiographyEntry') as $t) {
            $belongTo_Options[$t] = ('App\\' . $t)::get();
        }

        return view('biographies.entries.edit', [
            'belongTo_Options' => $belongTo_Options,
            'categories' => Property::where('type', 'BiographyEntryCategoryType')->orderBy('name')->get(),
            'entry' => $entry,
            'category' => $category
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delete($id)
    {
        if (Gate::denies('api-write:property')) {
            return view('errors.401');
        }

        $entry = Event::find($id);
        if (is_null($entry)) {
            return view('errors.404');
        }

        return view('biographies.entries.delete', [
            'entry' => $entry
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit_types()
    {
        if (Gate::denies('admin')) {
            return view('errors.401');
        }

        $types = Property::where('type', 'BiographyEntryCategory')->get();

        return view('biographies.entries.types.edit', [
            'types' => $types
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create_type()
    {
        if (Gate::denies('admin')) {
            return view('errors.401');
        }

        return view('biographies.entries.types.create');
    }
}
