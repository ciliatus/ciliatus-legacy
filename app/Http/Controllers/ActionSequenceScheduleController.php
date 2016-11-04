<?php

namespace App\Http\Controllers;

use App\Action;
use App\ActionSequence;
use App\ActionSequenceSchedule;
use App\Http\Transformers\ActionSequenceScheduleTransformer;
use App\Terrarium;
use Carbon\Carbon;
use DB;
use Gate;
use Illuminate\Http\Request;

use App\Http\Requests;

/**
 * Class ActionSequenceScheduleController
 * @package App\Http\Controllers
 */
class ActionSequenceScheduleController extends ApiController
{
    /**
     * @var ActionSequenceScheduleTransformer
     */
    protected $actionSequenceScheduleTransformer;

    /**
     * ActionSequenceScheduleController constructor.
     * @param ActionSequenceScheduleTransformer $_actionSequenceScheduleTransformer
     */
    public function __construct(ActionSequenceScheduleTransformer $_actionSequenceScheduleTransformer)
    {
        parent::__construct();
        $this->actionSequenceScheduleTransformer = $_actionSequenceScheduleTransformer;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if (Gate::denies('api-list')) {
            return $this->respondUnauthorized();
        }

        $action_sequence_schedules = $this->filter($request, ActionSequenceSchedule::with('sequence'))->get();

        foreach ($action_sequence_schedules as $ass) {
            $ass->running = $ass->running();
            $ass->will_run_today = $ass->will_run_today();
            $ass->ran_today = $ass->ran_today();
            $ass->is_overdue = $ass->is_overdue(10);
        }

        return $this->setStatusCode(200)->respondWithData(
            $this->actionSequenceScheduleTransformer->transformCollection(
                $action_sequence_schedules->toArray()
            ),
            $action_sequence_schedules
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

        $action = ActionSequenceSchedule::find($id);

        if (!$action) {
            return $this->respondNotFound('ActionSequenceSchedule not found');
        }

        return $this->setStatusCode(200)->respondWithData(
            $this->actionSequenceScheduleTransformer->transform(
                $action->toArray()
            )
        );
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {

        if (Gate::denies('api-write:action_sequence_schedule')) {
            return $this->respondUnauthorized();
        }

        $ass = ActionSequenceSchedule::find($id);
        if (is_null($ass)) {
            return $this->setStatusCode(422)->respondWithError('ActionSequenceSchedule not found');
        }

        $t = $ass->sequence->terrarium;

        $ass->delete();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('terraria/' . $t->id . '/edit'),
                'delay' => 1000
            ]
        ]);

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        if (Gate::denies('api-write:action_sequence_schedule')) {
            return $this->respondUnauthorized();
        }

        if ($request->has('action_sequence_id')) {
            $a = ActionSequence::find($request->input('action_sequence_id'));
            if (is_null($a)) {
                return $this->setStatusCode(422)->respondWithError('ActionSequence not found');
            }
        }

        $ass = ActionSequenceSchedule::create([
            'runonce' => $request->input('runonce') == 'on' ? true : false,
            'starts_at' => Carbon::parse($request->input('starts_at'))->format('H:i:s'),
            'action_sequence_id' => $request->input('action_sequence_id')
        ]);

        return $this->setStatusCode(200)->respondWithData(
            [
                'id'    =>  $ass->id
            ],
            [
                'redirect' => [
                    'uri'   => url('terraria/' . $ass->sequence->terrarium_id . '/edit'),
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

        if (Gate::denies('api-write:action_sequence_schedule')) {
            return $this->respondUnauthorized();
        }

        $action = ActionSequenceSchedule::find($request->input('id'));
        if (is_null($action)) {
            return $this->setStatusCode(404)->respondWithError('ActionSequenceSchedule not found');
        }

        if ($request->has('action_sequence_id')) {
            $a = ActionSequence::find($request->input('action_sequence_id'));
            if (is_null($a)) {
                return $this->setStatusCode(422)->respondWithError('ActionSequence not found');
            }
        }

        if ($request->has('terrarium_id')) {
            $a = Terrarium::find($request->input('terrarium_id'));
            if (is_null($a)) {
                return $this->setStatusCode(422)->respondWithError('Terrarium not found');
            }
        }

        $action->name = $request->input('name');
        $action->action_sequence_id = $request->input('action_sequence_id');
        $action->starts_at = $request->input('starts_at');
        $action->terrarium_id = $request->input('terrarium_id');
        $action->save();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('action_sequence_schedules'),
                'delay' => 1000
            ]
        ]);

    }
}
