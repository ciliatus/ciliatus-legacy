<?php

namespace App\Http\Controllers\Api;

use App\Action;
use App\ActionSequence;
use App\ActionSequenceSchedule;
use App\Http\Transformers\ActionSequenceScheduleTransformer;
use App\Repositories\ActionSequenceScheduleRepository;
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


        $action_sequence_schedules = ActionSequenceSchedule::with('sequence');

        $action_sequence_schedules = $this->filter($request, $action_sequence_schedules);


        /*
         * If raw is passed, pagination will be ignored
         * Permission api-list:raw is required
         */
        if ($request->has('raw') && Gate::allows('api-list:raw')) {
            $action_sequence_schedules = $action_sequence_schedules->get();
            foreach ($action_sequence_schedules as &$t) {
                $t = (new ActionSequenceScheduleRepository($t))->show();
            }

            return $this->setStatusCode(200)->respondWithData(
                $this->actionSequenceScheduleTransformer->transformCollection(
                    $action_sequence_schedules->toArray()
                )
            );

        }

        $action_sequence_schedules = $action_sequence_schedules->paginate(env('PAGINATION_PER_PAGE', 20));

        foreach ($action_sequence_schedules->items() as &$t) {
            $t = (new ActionSequenceScheduleRepository($t))->show();
        }

        return $this->setStatusCode(200)->respondWithPagination(
            $this->actionSequenceScheduleTransformer->transformCollection(
                $action_sequence_schedules->toArray()['data']
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

        $action = ActionSequenceSchedule::with('sequence')
                                        ->find($id);

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

        $asid = $ass->sequence->id;

        $ass->delete();

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

        if (Gate::denies('api-write:action_sequence_schedule')) {
            return $this->respondUnauthorized();
        }

        $a = ActionSequence::find($request->input('action_sequence'));
        if (is_null($a)) {
            return $this->setStatusCode(422)->respondWithError('ActionSequence not found');
        }

        $ass = ActionSequenceSchedule::create([
            'name' => 'ASS_' . $a->name . '_' . Carbon::parse($request->input('starts_at'))->format('H:i:s'),
            'runonce' => $request->input('runonce') == 'on' ? true : false,
            'starts_at' => Carbon::parse($request->input('starts_at'))->format('H:i:s'),
            'action_sequence_id' => $request->input('action_sequence')
        ]);

        return $this->setStatusCode(200)->respondWithData(
            [
                'id'    =>  $ass->id
            ],
            [
                'redirect' => [
                    'uri'   => url('action_sequences/' . $ass->sequence->id . '/edit'),
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

        if (Gate::denies('api-write:action_sequence_schedule')) {
            return $this->respondUnauthorized();
        }

        $action = ActionSequenceSchedule::find($id);
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

        $this->updateModelProperties($action, $request, [
            'name', 'action_sequence_id' => 'action_sequence', 'starts_at'
        ]);

        $action->save();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('action_sequence_schedules'),
                'delay' => 1000
            ]
        ]);

    }
}
