<?php

namespace App\Http\Controllers\Api;

use App\Action;
use App\ActionSequence;
use App\ActionSequenceIntention;
use App\Http\Transformers\ActionSequenceIntentionTransformer;
use App\LogicalSensor;
use App\Repositories\ActionSequenceIntentionRepository;
use App\Terrarium;
use Carbon\Carbon;
use DB;
use Gate;
use Illuminate\Http\Request;

use App\Http\Requests;

/**
 * Clasi ActionSequenceIntentionController
 * @package App\Http\Controllers
 */
class ActionSequenceIntentionController extends ApiController
{
    /**
     * @var ActionSequenceIntentionTransformer
     */
    protected $actionSequenceIntentionTransformer;

    /**
     * ActionSequenceIntentionController constructor.
     * @param ActionSequenceIntentionTransformer $_actionSequenceIntentionTransformer
     */
    public function __construct(ActionSequenceIntentionTransformer $_actionSequenceIntentionTransformer)
    {
        parent::__construct();
        $this->actionSequenceIntentionTransformer = $_actionSequenceIntentionTransformer;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if (Gate::denies('api-list')) {
            return $this->respondUnauthorized();
        }

        $action_sequence_intentions = ActionSequenceIntention::query();
        $action_sequence_intentions = $this->filter($request, $action_sequence_intentions);

        return $this->respondTransformedAndPaginated(
            $request,
            $action_sequence_intentions,
            $this->actionSequenceIntentionTransformer,
            'ActionSequenceIntention'
        );

    }


    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {

        if (Gate::denies('api-read')) {
            return $this->respondUnauthorized();
        }

        $asi = ActionSequence::query();
        $asi = $this->filter($request, $asi);
        $asi = $asi->find($id);

        if (!$asi) {
            return $this->respondNotFound('ActionSequenceIntention not found');
        }

        return $this->setStatusCode(200)->respondWithData(
            $this->actionSequenceIntentionTransformer->transform(
                $asi->toArray()
            )
        );
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {

        if (Gate::denies('api-write:action_sequence_intention')) {
            return $this->respondUnauthorized();
        }

        $asi = ActionSequenceIntention::find($id);
        if (is_null($asi)) {
            return $this->setStatusCode(422)->respondWithError('ActionSequenceIntention not found');
        }

        $asid = $asi->sequence->id;

        $asi->delete();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('action_sequences/' . $asid . '/edit'),
                'delay' => 1000
            ]
        ]);

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        if (Gate::denies('api-write:action_sequence_intention')) {
            return $this->respondUnauthorized();
        }

        $a = ActionSequence::find($request->input('action_sequence'));
        if (is_null($a)) {
            return $this->setStatusCode(422)->respondWithError('ActionSequence not found');
        }

        

        $asi = ActionSequenceIntention::create([
            'name' => 'ASI_' . $a->name . '_' . Carbon::parse($request->input('starts_at'))->format('H:i:s'),
            'type' => $request->input('type'),
            'intention' => $request->input('intention'),
            'minimum_timeout_minutes' => $request->input('minimum_timeout_minutes'),
            'timeframe_start' => Carbon::parse($request->input('timeframe_start'))->format('H:i:s'),
            'timeframe_end' => Carbon::parse($request->input('timeframe_end'))->format('H:i:s'),
            'action_sequence_id' => $request->input('action_sequence')
        ]);

        return $this->setStatusCode(200)->respondWithData(
            [
                'id'    =>  $asi->id
            ],
            [
                'redirect' => [
                    'uri'   => url('action_sequences/' . $asi->sequence->id . '/edit'),
                    'delay' => 100
                ]
            ]
        );

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {

        if (Gate::denies('api-write:action_sequence_intention')) {
            return $this->respondUnauthorized();
        }

        $intention = ActionSequenceIntention::find($id);
        if (is_null($intention)) {
            return $this->setStatusCode(404)->respondWithError('ActionSequenceIntention not found');
        }

        if ($request->has('action_sequence_id')) {
            $a = ActionSequence::find($request->input('action_sequence_id'));
            if (is_null($a)) {
                return $this->setStatusCode(422)->respondWithError('ActionSequence not found');
            }
        }

        $this->updateModelProperties($intention, $request, [
            'name', 'action_sequence_id' => 'action_sequence', 'type',
            'intention', 'minimum_timeout_minutes'
        ]);

        if ($request->has('minimum_timeout_minutes')) {
            $intention->minimum_timeout_minutes = $request->input('minimum_timeout_minutes');
        }

        if ($request->has('timeframe_start')) {
            $intention->timeframe_start = Carbon::parse($request->input('timeframe_start'))->format('H:i:s');
        }

        if ($request->has('timeframe_end')) {
            $intention->timeframe_end = Carbon::parse($request->input('timeframe_end'))->format('H:i:s');
        }

        $intention->save();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('action_sequences/' . $intention->sequence->id . '/edit'),
                'delay' => 100
            ]
        ]);

    }
}
