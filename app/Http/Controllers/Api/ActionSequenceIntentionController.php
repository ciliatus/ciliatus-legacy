<?php

namespace App\Http\Controllers\Api;

use App\ActionSequence;
use App\ActionSequenceIntention;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;

/**
 * Class ActionSequenceIntentionController
 * @package App\Http\Controllers
 */
class ActionSequenceIntentionController extends ApiController
{

    /**
     * ActionSequenceIntentionController constructor.
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->errorCodeNamespace = '13';
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

        if (Gate::denies('api-write:action_sequence_intention')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var ActionSequenceIntention $intention
         */
        $intention = ActionSequenceIntention::find($id);
        if (is_null($intention)) {
            return $this->respondRelatedModelNotFound(ActionSequenceIntention::class);
        }

        $id = $intention->sequence->id;

        $intention->delete();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('action_sequences/' . $id . '/edit')
            ]
        ]);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        if (Gate::denies('api-write:action_sequence_intention')) {
            return $this->respondUnauthorized();
        }

        $a = ActionSequence::find($request->input('action_sequence'));
        if (is_null($a)) {
            return $this->respondRelatedModelNotFound(ActionSequence::class);
        }

        /**
         * @var ActionSequenceIntention $intention
         */
        $intention = ActionSequenceIntention::create([
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
                'id'    =>  $intention->id
            ],
            [
                'redirect' => [
                    'uri'   => url('action_sequences/' . $intention->sequence->id . '/edit')
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

        if (Gate::denies('api-write:action_sequence_intention')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var ActionSequenceIntention $intention
         */
        $intention = ActionSequenceIntention::find($id);
        if (is_null($intention)) {
            return $this->respondNotFound();
        }

        if ($request->filled('action_sequence_id')) {
            $a = ActionSequence::find($request->input('action_sequence_id'));
            if (is_null($a)) {
                return $this->respondRelatedModelNotFound(ActionSequence::class);
            }
        }

        $this->updateModelProperties($intention, $request, [
            'name', 'action_sequence_id' => 'action_sequence', 'type',
            'intention', 'minimum_timeout_minutes'
        ]);

        if ($request->filled('minimum_timeout_minutes')) {
            $intention->minimum_timeout_minutes = $request->input('minimum_timeout_minutes');
        }

        if ($request->filled('timeframe_start')) {
            $intention->timeframe_start = Carbon::parse($request->input('timeframe_start'))->format('H:i:s');
        }

        if ($request->filled('timeframe_end')) {
            $intention->timeframe_end = Carbon::parse($request->input('timeframe_end'))->format('H:i:s');
        }

        $intention->save();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('action_sequences/' . $intention->sequence->id . '/edit')
            ]
        ]);

    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function skip($id)
    {
        if (Gate::denies('api-write:action_sequence_intention')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var ActionSequenceIntention $intention
         */
        $intention = ActionSequenceIntention::find($id);
        if (is_null($intention)) {
            return $this->respondNotFound();
        }

        $intention->next_start_not_before = Carbon::now()->addHours(2);
        $intention->save();

        return $this->respondWithData([
            'next_start_not_before' => $intention->next_start_not_before
        ]);

    }
}
