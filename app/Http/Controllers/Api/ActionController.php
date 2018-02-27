<?php

namespace App\Http\Controllers\Api;

use App\Action;
use App\ActionSequence;
use Gate;
use Illuminate\Http\Request;

/**
 * Class ActionController
 * @package App\Http\Controllers\Api
 */
class ActionController extends ApiController
{

    /**
     * ActionController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->errorCodeNamespace = '11';
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return parent::default_index($request);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        return parent::default_show($request, $id);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Request $request, $id)
    {

        if (Gate::denies('api-write:action')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var Action $action
         */
        $action = Action::find($id);
        if (is_null($action)) {
            return $this->respondNotFound();
        }

        $action->delete();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('actions')
            ]
        ]);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        if (Gate::denies('api-write:action')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var ActionSequence $sequence
         */
        $sequence = ActionSequence::find($request->input('action_sequence'));
        if (is_null($sequence)) {
            return $this->respondRelatedModelNotFound(ActionSequence::class);
        }

        list($component_type, $component_id) = explode('|', $request->input('component'));
        if (!class_exists('App\\' . $component_type)) {
            return $this->respondRelatedModelNotFound('App\\' . $component_type);
        }

        $component_type_fq = 'App\\' . $component_type;
        $component = $component_type_fq::find($component_id);
        if (is_null($component)) {
            return $this->respondRelatedModelNotFound($component_type_fq);
        }

        /**
         * @var Action $action
         */
        $action = Action::create([
            'action_sequence_id' => $request->input('action_sequence'),
            'target_type' => $component_type,
            'target_id' => $component_id,
            'desired_state' => $request->input('state'),
            'duration_minutes' => (int)$request->input('duration'),
            'sequence_sort_id' => count($sequence->actions) + 1
        ]);

        $this->update($request, $action->id);

        return $this->respondWithData(
            [
                'id'    =>  $action->id
            ],
            [
                'redirect' => [
                    'uri'   => url('action_sequences/' . $sequence->id . '/edit')
                ]
            ]
        );

    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {

        if (Gate::denies('api-write:action')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var Action $action
         */
        $action = Action::find($id);
        if (is_null($action)) {
            return $this->respondNotFound();
        }

        if ($request->filled('action_sequence_id')) {
            $asi = ActionSequence::find($request->input('action_sequence_id'));
            if (is_null($asi)) {
                return $this->respondRelatedModelNotFound(ActionSequence::class);
            }
        }

        if ($request->filled('wait_for_started_action_id')) {
            $a = Action::find($request->input('wait_for_started_action_id'));
            if (is_null($a)) {
                return $this->respondRelatedModelNotFound(Action::class);
            }
        }

        if ($request->filled('wait_for_finished_action_id')) {
            $a = Action::find($request->input('wait_for_finished_action_id'));
            if (is_null($a)) {
                return $this->respondRelatedModelNotFound(Action::class);
            }
        }

        if ($request->filled('component')) {
            list($component_type, $component_id) = explode('|', $request->input('component'));
            if (!class_exists('App\\' . $component_type)) {
                return $this->setStatusCode(422)->respondWithError('Component type not found');
            }
            $component_type_fq = 'App\\' . $component_type;
            $component = $component_type_fq::find($component_id);
            if (is_null($component)) {
                return $this->respondRelatedModelNotFound($component_type_fq);
            }

            $action->target_type = $component_type;
            $action->target_id = $component_id;
        }

        $this->updateModelProperties($action, $request, [
            'action_sequence_id', 'desired_state',
            'duration_minutes', 'wait_for_started_action_id', 'wait_for_finished_action_id'
        ]);

        $action->save();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('actions')
            ]
        ]);

    }
}
