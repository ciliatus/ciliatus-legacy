<?php

namespace App\Http\Controllers\Api;

use App\Action;
use App\ActionSequence;
use App\Http\Transformers\ActionSequenceTransformer;
use App\Terrarium;
use Gate;
use Illuminate\Http\Request;

use App\Http\Requests;

class ActionSequenceController extends ApiController
{
    /**
     * @var ActionSequenceTransformer
     */
    protected $actionTransformer;

    /**
     * ActionSequenceController constructor.
     * @param ActionSequenceTransformer $_actionTransformer
     */
    public function __construct(ActionSequenceTransformer $_actionTransformer)
    {
        parent::__construct();
        $this->actionTransformer = $_actionTransformer;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        if (Gate::denies('api-list')) {
            return $this->respondUnauthorized();
        }

        $actions = ActionSequence::paginate(10);

        return $this->setStatusCode(200)->respondWithPagination(
            $this->actionTransformer->transformCollection(
                $actions->toArray()['data']
            ),
            $actions
        );
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {

        if (Gate::denies('api-read')) {
            return $this->respondUnauthorized();
        }

        $action = ActionSequence::find($id);

        if (!$action) {
            return $this->respondNotFound('ActionSequence not found');
        }

        return $this->setStatusCode(200)->respondWithData(
            $this->actionTransformer->transform(
                $action->toArray()
            )
        );
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {

        if (Gate::denies('api-write:action_sequence')) {
            return $this->respondUnauthorized();
        }

        $action = ActionSequence::find($id);
        if (is_null($action)) {
            return $this->setStatusCode(404)->respondWithError('ActionSequence not found');
        }

        $action->delete();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('/'),
                'delay' => 2000
            ]
        ]);

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        if (Gate::denies('api-write:action_sequence')) {
            return $this->respondUnauthorized();
        }

        $as = ActionSequence::create();

        if ($request->has('terrarium_id')) {
            $t = Terrarium::find($request->input('terrarium_id'));
            if (is_null($t)) {
                return $this->setStatusCode(422)->respondWithError('Terrarium not found');
            }
        }
        else {
            return $this->setStatusCode(422)->respondWithError('No Terrarium selected');
        }

        if ($request->has('name')) {
            $name = $request->input('name');
        }
        else {
            $name = 'AS_' . $request->input('template') . '_' . $t->name;
        }

        $as->name = $name;
        $as->duration_minutes = $request->input('duration_minutes');
        $as->terrarium_id = $request->input('terrarium_id');
        $as->save();

        switch ($request->input('template')) {
            case 'irrigate':
                $a_prev = null;
                $sort_id = 1;
                if (is_null($t->valves))
                    break;

                foreach ($t->valves as $v) {
                    $a = Action::create();
                    $a->action_sequence_id = $as->id;
                    $a->target_type = 'Valve';
                    $a->target_id = $v->id;
                    $a->desired_state = 'running';
                    $a->duration_minutes = $as->duration_minutes;
                    $a->wait_for_started_action_id = $a_prev;
                    $a->sequence_sort_id = $sort_id;
                    $a->save();
                    $sort_id++;
                    $a_prev = $a->id;

                    if (!is_null($v->pump)) {
                        $a = Action::create();
                        $a->action_sequence_id = $as->id;
                        $a->target_type = 'Pump';
                        $a->target_id = $v->pump->id;
                        $a->desired_state = 'running';
                        $a->duration_minutes = $as->duration_minutes;
                        $a->wait_for_started_action_id = $a_prev;
                        $a->sequence_sort_id = $sort_id;
                        $a->save();
                        $sort_id++;
                        $a_prev = $a->id;
                    }
                }
                break;
        }

        return $this->setStatusCode(200)->respondWithData(
            [
                'id'    =>  $as->id
            ],
            [
                'redirect' => [
                    'uri'   => url('terraria/' . $as->terrarium_id . '/edit'),
                    'delay' => 1000
                ]
            ]
        );

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {

        if (Gate::denies('api-write:action_sequence')) {
            return $this->respondUnauthorized();
        }

        $action_sequence = ActionSequence::find($request->input('id'));
        if (is_null($action_sequence)) {
            return $this->setStatusCode(404)->respondWithError('ActionSequence not found');
        }

        $action_sequence->name = $request->input('name');
        $action_sequence->save();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('terraria/' . $action_sequence->terrarium_id . '/edit'),
                'delay' => 1000
            ]
        ]);

    }
}
