<?php

namespace App\Http\Controllers\Web;

use App\CustomComponentType;
use App\Http\Controllers\Controller;
use App\Property;
use Gate;
use Illuminate\Http\Request;

/**
 * Class AdminController
 * @package App\Http\Controllers\Web
 */
class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        //
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
     * @return void
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $id
     * @return void
     */
    public function edit($id)
    {
        //
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


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function categories()
    {
        if (Gate::denies('admin')) {
            return response()->view('errors.401', [], 401);
        }

        return view('admin.categories', [
            'animal_feeding_types' => Property::where('type', 'AnimalFeedingType')->get(),
            'bio_categories' => Property::where('type', 'BiographyEntryCategoryType')->get(),
            'custom_component_types' => CustomComponentType::get()
        ]);
    }
}
