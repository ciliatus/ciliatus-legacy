<?php

namespace App\Http\Controllers\Api;

use App\Action;
use App\ActionSequence;
use App\ActionSequenceIntention;
use App\ActionSequenceSchedule;
use App\ActionSequenceTrigger;
use App\Events\SystemStatusUpdated;
use App\Http\Transformers\ActionSequenceTransformer;
use App\Property;
use App\Repositories\ActionSequenceRepository;
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
            $action_sequences = $action_sequences->get();

            foreach ($action_sequences as &$as) {
                $as = (new ActionSequenceRepository($as))->show();
            }

            return $this->setStatusCode(200)->respondWithData(
                $this->actionSequenceTransformer->transformCollection(
                    $action_sequences->toArray()
                )
            );
        }

        $action_sequences = $action_sequences->paginate(env('PAGINATION_PER_PAGE', 20));

        foreach ($action_sequences->items() as &$as) {
            $as = (new ActionSequenceRepository($as))->show();
        }

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
                    'uri'   => url('terraria/' . $as->terrarium_id),
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

        if ($request->has('runonce')) {
            $as->runonce = $request->input('runonce') == 'on' ? true : false;
        }


        $as->name = $name;
        $as->duration_minutes = $request->input('duration_minutes');
        $as->terrarium_id = $request->input('terrarium');
        $as->save();

        if ($request->has('template')) {
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
                $as->generateActionByTemplate($template_name);

                if ($request->input('generate_intentions') == 'On') {
                    $as->generateIntentionsByTemplate($template_name);
                }
            }
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
    public function update(Request $request, $id)
    {

        if (Gate::denies('api-write:action_sequence')) {
            return $this->respondUnauthorized();
        }

        $action_sequence = ActionSequence::find($id);
        if (is_null($action_sequence)) {
            return $this->setStatusCode(404)->respondWithError('ActionSequence not found');
        }

        $this->updateModelProperties($action_sequence, $request, [
            'name'
        ]);

        if ($request->has('runonce')) {
            $action_sequence->runonce = $request->input('runonce') == 'on' ? true : false;
        }

        $action_sequence->save();

        return $this->setStatusCode(200)->respondWithData([],
            [
                'redirect' => [
                    'uri'   => url('terraria/' . $action_sequence->terrarium_id),
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
