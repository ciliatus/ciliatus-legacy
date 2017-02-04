<?php

namespace App\Http\Controllers\Api;

use App\Action;
use App\ActionSequence;
use App\ActionSequenceSchedule;
use App\ActionSequenceTrigger;
use App\Events\SystemStatusUpdated;
use App\Http\Transformers\ActionSequenceTransformer;
use App\Property;
use App\RunningAction;
use App\Terrarium;
use Auth;
use Gate;
use Illuminate\Http\Request;

use App\Http\Requests;

/**
 * Class ActionSequenceController
 * @package App\Http\Controllers\Api
 */
class ActionSequenceController extends ApiController
{
    /**
     * @var ActionSequenceTransformer
     */
    protected $actionSequenceTransformer;

    /**
     * ActionSequenceController constructor.
     * @param ActionSequenceTransformer $_actionSequenceTransformer
     */
    public function __construct(ActionSequenceTransformer $_actionSequenceTransformer)
    {
        parent::__construct();
        $this->actionSequenceTransformer = $_actionSequenceTransformer;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if (Gate::denies('api-list')) {
            return $this->respondUnauthorized();
        }

        $action_sequences = ActionSequence::with('schedules')
                                          ->with('triggers')
                                          ->with('intentions')
                                          ->with('terrarium');

        $action_sequences = $this->filter($request, $action_sequences);

        /*
         * If raw is passed, pagination will be ignored
         * Permission api-list:raw is required
         */
        if ($request->has('raw') && Gate::allows('api-list:raw')) {

            return $this->setStatusCode(200)->respondWithData(
                $this->actionSequenceTransformer->transformCollection(
                    $action_sequences->get()->toArray()
                )
            );
        }

        $action_sequences = $action_sequences->paginate(env('PAGINATION_PER_PAGE', 20));

        return $this->setStatusCode(200)->respondWithPagination(
            $this->actionSequenceTransformer->transformCollection(
                $action_sequences->toArray()['data']
            ),
            $action_sequences
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

        $action = ActionSequence::with('schedules')
                                ->with('triggers')
                                ->with('intentions')
                                ->with('terrarium')
                                ->find($id);

        if (!$action) {
            return $this->respondNotFound('ActionSequence not found');
        }

        return $this->setStatusCode(200)->respondWithData(
            $this->actionSequenceTransformer->transform(
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

        $as = ActionSequence::find($id);
        if (is_null($as)) {
            return $this->setStatusCode(404)->respondWithError('ActionSequence not found');
        }

        $as->delete();

        return $this->setStatusCode(200)->respondWithData([],
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
    public function store(Request $request)
    {

        if (Gate::denies('api-write:action_sequence')) {
            return $this->respondUnauthorized();
        }

        $as = ActionSequence::create();

        if ($request->has('terrarium')) {
            $t = Terrarium::find($request->input('terrarium'));
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
            if ($request->has('template')) {
                $name = trans('labels.' . $request->input('template')) . ' ' . $t->display_name;
            }
            else {
                $name = trans_choice('components.action_sequences', 1) . ' ' . $t->display_name;
            }
        }

        $as->name = $name;
        $as->duration_minutes = $request->input('duration_minutes');
        $as->terrarium_id = $request->input('terrarium');
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
                    'uri'   => url('action_sequences/' . $as->id . '/edit'),
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

        if ($request->has('name')) {
            $action_sequence->name = $request->input('name');
        }

        $action_sequence->save();

        return $this->setStatusCode(200)->respondWithData([],
            [
                'redirect' => [
                    'uri'   => url('terraria/' . $action_sequence->terrarium_id . '/edit'),
                    'delay' => 1000
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
