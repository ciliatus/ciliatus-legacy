<?php

namespace App\Http\Controllers\Api;

use App\ActionSequence;
use App\ActionSequenceSchedule;
use App\Terrarium;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;

/**
 * Class ActionSequenceScheduleController
 * @package App\Http\Controllers
 */
class ActionSequenceScheduleController extends ApiController
{

    /**
     * ActionSequenceScheduleController constructor.
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

        if (Gate::denies('api-write:action_sequence_schedule')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var ActionSequenceSchedule $schedule
         */
        $schedule = ActionSequenceSchedule::find($id);
        if (is_null($schedule)) {
            return $this->setStatusCode(422)->respondWithError('ActionSequenceSchedule not found');
        }

        $id = $schedule->sequence->id;

        $schedule->delete();

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

        if (Gate::denies('api-write:action_sequence_schedule')) {
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
         * @var ActionSequenceSchedule $schedule
         */
        $schedule = ActionSequenceSchedule::create([
            'name' => 'ASS_' . $sequence->name . '_' . Carbon::parse($request->input('starts_at'))->format('H:i:s'),
            'runonce' => $request->input('runonce') == 'on' ? true : false,
            'starts_at' => Carbon::parse($request->input('starts_at'))->format('H:i:s'),
            'action_sequence_id' => $request->input('action_sequence')
        ]);


        $schedule->updateWeekdays($this->getWeekdaysArrayFromRequest($request));

        return $this->setStatusCode(200)->respondWithData(
            [
                'id'    =>  $schedule->id
            ],
            [
                'redirect' => [
                    'uri'   => url('action_sequences/' . $schedule->sequence->id . '/edit')
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

        if (Gate::denies('api-write:action_sequence_schedule')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var ActionSequenceSchedule $schedule
         */
        $schedule = ActionSequenceSchedule::find($id);
        if (is_null($schedule)) {
            return $this->setStatusCode(404)->respondWithError('ActionSequenceSchedule not found');
        }

        if ($request->filled('action_sequence')) {
            $a = ActionSequence::find($request->input('action_sequence_id'));
            if (is_null($a)) {
                return $this->setStatusCode(422)->respondWithError('ActionSequence not found');
            }
        }

        $this->updateModelProperties($schedule, $request, [
            'name', 'action_sequence_id' => 'action_sequence', 'starts_at'
        ]);

        $schedule->updateWeekdays($this->getWeekdaysArrayFromRequest($request));

        $schedule->save();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('action_sequence_schedules')
            ]
        ]);

    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function skip($id)
    {
        if (Gate::denies('api-write:action_sequence_schedule')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var ActionSequenceSchedule $schedule
         */
        $schedule = ActionSequenceSchedule::find($id);
        if (is_null($schedule)) {
            return $this->setStatusCode(404)->respondWithError('ActionSequenceSchedule not found');
        }

        $schedule->next_start_not_before = Carbon::now()->addDays(1)->subMinute(1);
        $schedule->save();

        return $this->respondWithData([
            'next_start_not_before' => $schedule->next_start_not_before
        ]);

    }

    /**
     * @param Request $request
     * @return array
     */
    protected function getWeekdaysArrayFromRequest(Request $request)
    {
        $weekdays = [];
        for ($i = 0; $i < 7; $i++) {
            $input_name = 'weekday_' . $i;
            $value = $request->filled($input_name) && $request->input($input_name) == 'on' ? 1 : 0;
            $weekdays[$i] = $value;
        }

        return $weekdays;
    }

}
