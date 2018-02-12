<?php

namespace App\Http\Controllers\Api;

use App\Action;
use App\ActionSequence;
use App\ActionSequenceTrigger;
use App\LogicalSensor;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;

/**
 * Class ActionSequenceTriggerController
 * @package App\Http\Controllers
 */
class ActionSequenceTriggerController extends ApiController
{

    /**
     * ActionSequenceTriggerController constructor.
     */
    public function __construct()
    {
        parent::__construct();
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

        if (Gate::denies('api-write:action_sequence_trigger')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var ActionSequenceTrigger $trigger
         */
        $trigger = ActionSequenceTrigger::find($id);
        if (is_null($trigger)) {
            return $this->setStatusCode(422)->respondWithError('ActionSequenceTrigger not found');
        }

        $id = $trigger->sequence->id;

        $trigger->delete();

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

        if (Gate::denies('api-write:action_sequence_trigger')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var ActionSequence $sequence
         */
        $sequence = ActionSequence::find($request->input('action_sequence'));
        if (is_null($sequence)) {
            return $this->setStatusCode(422)->respondWithError('ActionSequence not found');
        }

        /**
         * @var LogicalSensor $ls
         */
        $ls = LogicalSensor::find($request->input('logical_sensor'));
        if (is_null($ls)) {
            return $this->setStatusCode(422)->respondWithError('LogicalSensor not found');
        }

        if (!in_array($request->input('reference_value_comparison_type'), ['equal', 'lesser', 'greater'])) {
            return $this->setStatusCode(422)->respondWithError('Unknown reference value comparison type');
        }

        /**
         * @var ActionSequenceTrigger $trigger
         */
        $trigger = ActionSequenceTrigger::create([
            'name' => 'AST_' . $sequence->name . '_' . Carbon::parse($request->input('starts_at'))->format('H:i:s'),
            'logical_sensor_id' => $ls->id,
            'reference_value' => $request->input('reference_value'),
            'reference_value_comparison_type' => $request->input('reference_value_comparison_type'),
            'reference_value_duration_threshold_minutes' => $request->input('reference_value_duration_threshold_minutes'),
            'minimum_timeout_minutes' => $request->input('minimum_timeout_minutes'),
            'timeframe_start' => Carbon::parse($request->input('timeframe_start'))->format('H:i:s'),
            'timeframe_end' => Carbon::parse($request->input('timeframe_end'))->format('H:i:s'),
            'action_sequence_id' => $request->input('action_sequence')
        ]);

        return $this->setStatusCode(200)->respondWithData(
            [
                'id'    =>  $trigger->id
            ],
            [
                'redirect' => [
                    'uri'   => url('action_sequences/' . $trigger->sequence->id . '/edit')
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

        if (Gate::denies('api-write:action_sequence_trigger')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var ActionSequenceTrigger $trigger
         */
        $trigger = ActionSequenceTrigger::find($id);
        if (is_null($trigger)) {
            return $this->setStatusCode(404)->respondWithError('ActionSequenceTrigger not found');
        }

        if ($request->filled('action_sequence_id')) {
            $a = ActionSequence::find($request->input('action_sequence_id'));
            if (is_null($a)) {
                return $this->setStatusCode(422)->respondWithError('ActionSequence not found');
            }
        }

        if ($request->filled('logical_sensor')) {
            $ls = LogicalSensor::find($request->input('logical_sensor'));
            if (is_null($ls)) {
                return $this->setStatusCode(422)->respondWithError('LogicalSensor not found');
            }
        }

        if ($request->filled('reference_value_comparison_type')) {
            if (!in_array($request->input('reference_value_comparison_type'), ['equal', 'lesser', 'greater'])) {
                return $this->setStatusCode(422)->respondWithError('Unknown reference value comparison type');
            }
        }

        $this->updateModelProperties($trigger, $request, [
            'name', 'action_sequence_id' => 'action_sequence', 'logical_sensor_id' => 'logical_sensor',
            'reference_value_comparison_type', 'reference_value', 'minimum_timeout_minutes',
            'reference_value_duration_threshold_minutes'
        ]);

        if ($request->filled('timeframe_start')) {
            $trigger->timeframe_start = Carbon::parse($request->input('timeframe_start'))->format('H:i:s');
        }

        if ($request->filled('timeframe_end')) {
            $trigger->timeframe_end = Carbon::parse($request->input('timeframe_end'))->format('H:i:s');
        }

        $trigger->save();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('action_sequences/' . $trigger->sequence->id . '/edit')
            ]
        ]);

    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function skip($id)
    {
        if (Gate::denies('api-write:action_sequence_trigger')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var ActionSequenceTrigger $trigger
         */
        $trigger = ActionSequenceTrigger::find($id);
        if (is_null($trigger)) {
            return $this->setStatusCode(404)->respondWithError('ActionSequenceSchedule not found');
        }

        $trigger->next_start_not_before = Carbon::now()->addHours(2);
        $trigger->save();

        return $this->respondWithData([
            'next_start_not_before' => $trigger->next_start_not_before
        ]);
    }
}
