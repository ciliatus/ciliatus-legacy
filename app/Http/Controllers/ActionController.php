<?php

namespace App\Http\Controllers;

use App\Action;
use App\ActionSequence;
use App\Http\Transformers\ActionTransformer;
use Gate;
use Illuminate\Http\Request;

use App\Http\Requests;

class ActionController extends ApiController
{
    /**
     * @var ActionTransformer
     */
    protected $actionTransformer;

    /**
     * ActionController constructor.
     * @param ActionTransformer $_actionTransformer
     */
    public function __construct(ActionTransformer $_actionTransformer)
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

        $actions = Action::paginate(10);

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

        $action = Action::find($id);

        if (!$action) {
            return $this->respondNotFound('Action not found');
        }

        $action->action_object = $action->action_object();
        $action->wait_for_started_action_object = $action->wait_for_started_action_object();
        $action->wait_for_finished_action_object = $action->wait_for_finished_action_object();

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

        if (Gate::denies('api-write:action')) {
            return $this->respondUnauthorized();
        }

        $action = Action::find($id);
        if (is_null($action)) {
            return $this->setStatusCode(404)->respondWithError('Action not found');
        }

        $action->delete();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('actions'),
                'delay' => 2000
            ]
        ]);

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        if (Gate::denies('api-write:action')) {
            return $this->respondUnauthorized();
        }

        $action = Action::create();
        $action->save();

        return $this->setStatusCode(200)->respondWithData(
            [
                'id'    =>  $action->id
            ],
            [
                'redirect' => [
                    'uri'   => url('actions/' . $action->id . '/edit'),
                    'delay' => 100
                ]
            ]
        );

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {

        if (Gate::denies('api-write:action')) {
            return $this->respondUnauthorized();
        }

        $action = Action::find($request->input('id'));
        if (is_null($action)) {
            return $this->setStatusCode(404)->respondWithError('Action not found');
        }

        if ($request->has('action_sequence_id')) {
            $asi = ActionSequence::find($request->input('action_sequence_id'));
            if (is_null($asi)) {
                return $this->setStatusCode(422)->respondWithError('ActionSequence not found');
            }
        }

        if ($request->has('wait_for_started_action_id')) {
            $a = Action::find($request->input('wait_for_started_action_id'));
            if (is_null($a)) {
                return $this->setStatusCode(422)->respondWithError('Action not found');
            }
        }

        if ($request->has('wait_for_finished_action_id')) {
            $a = Action::find($request->input('wait_for_finished_action_id'));
            if (is_null($a)) {
                return $this->setStatusCode(422)->respondWithError('Action not found');
            }
        }

        $action->action_sequence_id = $request->input('action_sequence_id');
        $action->target_type = $request->input('target_type');
        $action->target_id = $request->input('target_id');
        $action->desired_state = $request->input('desired_state');
        $action->duration_minutes = $request->input('duration_minutes');
        $action->wait_for_started_action_id = $request->input('wait_for_started_action_id');
        $action->wait_for_finished_action_id = $request->input('wait_for_finished_action_id');

        $action->save();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('actions'),
                'delay' => 1000
            ]
        ]);

    }
}
