<?php

namespace App\Http\Controllers\Api;

use App\ActionSequence;
use App\ActionSequenceIntention;
use App\ActionSequenceSchedule;
use App\ActionSequenceTrigger;
use App\Events\SystemStatusUpdated;
use App\Property;
use App\RunningAction;
use App\Terrarium;
use Gate;
use Illuminate\Http\Request;

/**
 * Class ActionSequenceController
 * @package App\Http\Controllers\Api
 */
class ActionSequenceController extends ApiController
{

    /**
     * ActionSequenceController constructor.
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \ErrorException
     */
    public function index(Request $request)
    {
        return parent::default_index($request);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \ErrorException
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

        if (Gate::denies('api-write:action_sequence')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var ActionSequence $action_sequence
         */
        $action_sequence = ActionSequence::find($id);
        if (is_null($action_sequence)) {
            return $this->setStatusCode(404)->respondWithError('ActionSequence not found');
        }

        $action_sequence->delete();

        return $this->setStatusCode(200)->respondWithData([],
            [
                'redirect' => [
                    'uri'   => url('terraria/' . $action_sequence->terrarium_id)
                ]
            ]
        );

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        if (Gate::denies('api-write:action_sequence')) {
            return $this->respondUnauthorized();
        }

        if ($request->filled('terrarium')) {
            $t = Terrarium::find($request->input('terrarium'));
            if (is_null($t)) {
                return $this->setStatusCode(422)->respondWithError('Terrarium not found');
            }
        }
        else {
            return $this->setStatusCode(422)->respondWithError('No Terrarium selected');
        }

        /**
         * @var ActionSequence $as
         */
        $action_sequence = new ActionSequence();

        if ($request->filled('name')) {
            $name = $request->input('name');
        }
        else {
            if ($request->filled('template')) {
                $name = trans('labels.' . $request->input('template')) . ' ' . $t->display_name;
            }
            else {
                $name = trans_choice('components.action_sequences', 1) . ' ' . $t->display_name;
            }
        }

        if ($request->filled('runonce')) {
            $action_sequence->runonce = $request->input('runonce') == 'on' ? true : false;
        }


        $action_sequence->name = $name;
        $action_sequence->duration_minutes = $request->input('duration_minutes');
        $action_sequence->terrarium_id = $request->input('terrarium');
        $action_sequence->save();

        if ($request->filled('template')) {
            switch ($request->input('template')) {
                case 'irrigate':
                    $template_name = ActionSequence::TEMPLATE_IRRIGATION;
                    break;
                case 'ventilate':
                    $template_name = ActionSequence::TEMPLATE_VENTILATE;
                    break;
                case 'heat_up':
                    $template_name = ActionSequence::TEMPLATE_HEAT_UP;
                    break;
                case 'cool_down':
                    $template_name = ActionSequence::TEMPLATE_COOL_DOWN;
                    break;
                default:
                    $template_name = null;
            }

            if (!is_null($template_name)) {
                $action_sequence->generateActionsByTemplate($template_name);

                if ($request->input('generate_intentions') == 'On') {
                    $action_sequence->generateIntentionsByTemplate($template_name);
                }
            }
        }

        return $this->setStatusCode(200)->respondWithData(
            [
                'id'    =>  $action_sequence->id
            ],
            [
                'redirect' => [
                    'uri'   => url('action_sequences/' . $action_sequence->id . '/edit')
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

        if (Gate::denies('api-write:action_sequence')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var ActionSequence $action_sequence
         */
        $action_sequence = ActionSequence::find($id);
        if (is_null($action_sequence)) {
            return $this->setStatusCode(404)->respondWithError('ActionSequence not found');
        }

        $this->updateModelProperties($action_sequence, $request, [
            'name'
        ]);

        if ($request->filled('runonce')) {
            $action_sequence->runonce = $request->input('runonce') == 'on' ? true : false;
        }

        $action_sequence->save();

        return $this->setStatusCode(200)->respondWithData([],
            [
                'redirect' => [
                    'uri'   => url('terraria/' . $action_sequence->terrarium_id)
                ]
            ]
        );

    }

    /**
     *
     */
    public function stop_all()
    {
        Property::create([
            'belongsTo_type' => 'System',
            'belongsTo_id' => '00000000-0000-0000-0000-000000000000',
            'type' => 'SystemProperty',
            'name' => 'stop_all_action_sequences'
        ]);

        foreach (RunningAction::get() as $ra) {
            $ra->delete();
        }

        foreach (ActionSequenceSchedule::get() as $ass) {
            if ($ass->running()) {
                $ass->finish();
            }
        }

        foreach (ActionSequenceTrigger::get() as $ast) {
            if ($ast->running()) {
                $ast->finish();
            }
        }

        foreach (ActionSequenceIntention::get() as $asi) {
            if ($asi->running()) {
                $asi->finish();
            }
        }

        broadcast(new SystemStatusUpdated());

        return $this->setStatusCode(200)->respondWithData([],
            [
                'redirect' => [
                    'uri'   => url('/'),
                ]
            ]
        );
    }

    /**
     *
     */
    public function resume_all()
    {
        foreach (Property::where('type', 'SystemProperty')->where('name', 'stop_all_action_sequences')->get() as $p) {
            $p->delete();
        }

        broadcast(new SystemStatusUpdated());

        return $this->setStatusCode(200)->respondWithData([],
            [
                'redirect' => [
                    'uri'   => url('/'),
                ]
            ]
        );
    }

}
