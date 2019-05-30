<?php

namespace App\Http\Controllers\Web;

use App\Animal;
use App\Http\Controllers\Controller;
use App\Room;
use App\Terrarium;
use App\Valve;
use Illuminate\Http\Request;

/**
 * Class TerrariumController
 * @package App\Http\Controllers
 */
class TerrariumController extends Controller
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
        return view('terraria.index', [
            'terraria' => Terrarium::get()
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
        return view('terraria.create', [
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
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $terrarium = Terrarium::find($id);
        if (is_null($terrarium)) {
            return response()->view('errors.404', [], 404);
        }

        return view('terraria.show', [
            'terrarium' => $terrarium
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $terrarium = Terrarium::find($id);

        if (is_null($terrarium)) {
            return response()->view('errors.404', [], 404);
        }

        $valves = Valve::where(function ($query) use ($terrarium) {
            $query->where('terrarium_id', $terrarium->id)
                ->orWhereNull('terrarium_id');
        })->get();

        $rooms = Room::get();

        $animals = Animal::where(function ($query) use ($terrarium) {
            $query->where('terrarium_id', $terrarium->id)
                  ->orWhere(function ($filter_query) use ($terrarium) {
                      $filter_query->whereDoesntHave('properties', function ($inner_query) {
                          $inner_query->where('type', 'ModelNotActive');
                      })
                      ->where(function ($inner_query) use ($terrarium) {
                          $inner_query->whereNull('terrarium_id')
                                      ->whereNull('death_date');
                      });
                  });
            })->get();

        return view('terraria.edit', [
            'terrarium'     => $terrarium,
            'valves'        => $valves,
            'animals'       => $animals,
            'rooms'         => $rooms
        ]);
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
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        $terrarium = Terrarium::find($id);

        if (is_null($terrarium)) {
            return response()->view('errors.404', [], 404);
        }

        return view('terraria.delete', [
            'terrarium'     => $terrarium
        ]);
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
