<?php

namespace App\Http\Controllers\Api;

use App\ActionSequence;
use App\ActionSequenceIntention;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;

/**
 * Clasi ActionSequenceIntentionController
 * @package App\Http\Controllers
 */
class ActionSequenceIntentionController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
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
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        return parent::default_show($request, $id);
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
