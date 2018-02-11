<?php

namespace App\Observers;


use App\ActionSequenceSchedule;
use App\RunningAction;
use Carbon\Carbon;

/**
 * Class ActionSequenceScheduleObserver
 * @package App\Observers
 */
class ActionSequenceScheduleObserver
{
    /**
     * @param  ActionSequenceSchedule $actionSequenceSchedule
     * @return void
     */
    public function creating(ActionSequenceSchedule $actionSequenceSchedule)
    {
        if ($actionSequenceSchedule->startsToday()->lt(Carbon::now()->subMinutes(10))) {
            $actionSequenceSchedule->last_start_at = Carbon::now();
            $actionSequenceSchedule->last_finished_at = Carbon::now();
        }
    }

    /**
     * @param  ActionSequenceSchedule $actionSequenceSchedule
     * @return void
     */
    public function deleting(ActionSequenceSchedule $actionSequenceSchedule)
    {
        foreach (RunningAction::where('action_sequence_schedule_id', $actionSequenceSchedule->id)->get() as $ra) {
            $ra->delete();
        }
    }
}