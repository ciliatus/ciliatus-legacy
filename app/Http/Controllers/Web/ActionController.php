<?php

namespace App\Http\Controllers\Web;

use App\GenericComponentType;
use App\Http\Controllers\Controller;
use App\Action;
use App\ActionSequence;
use App\Pump;
use App\Valve;
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
    public function create(Request $request)
    {
        $components = [
            'Valves' => [
                'display_name' => trans_choice('components.valves', 2),
                'tech_name' => 'Valve',
                'objects' => []
            ],
            'Pumps' => [
                'display_name' => trans_choice('components.pumps', 2),
                'tech_name' => 'Pump',
                'objects' => []
            ]
        ];
        foreach (Valve::get() as $v) {
            $components['Valves']['objects'][] = [
                'id' => $v->id,
                'name' => $v->name,
                'states' => $v->states()
            ];
        }
        foreach (Pump::get() as $p) {
            $components['Pumps']['objects'][] = [
                'id' => $p->id,
                'name' => $p->name,
                'states' => $p->states()
            ];
        }
        foreach (GenericComponentType::get() as $gct) {
            $components['GenericComponent_' . $gct->id]['display_name'] = $gct->name_plural;
            $components['GenericComponent_' . $gct->id]['tech_name'] = 'GenericComponent';
            $components['GenericComponent_' . $gct->id]['objects'] = [];
            foreach ($gct->components as $gc) {
                $components['GenericComponent_' . $gct->id]['objects'][] = [
                    'id' => $gc->id,
                    'name' => $gc->name,
                    'states' => array_column($gc->states->toArray(), 'name')
                ];
            }
        }

        return view('actions.create', [
            'action_sequences' => ActionSequence::get(),
            'preset' => $request->input('preset'),
            'components' => $components
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
