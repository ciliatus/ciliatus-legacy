<?php

namespace App\Observers;

use App\RunningAction;

class ActionSequenceTriggerObserver
{

    /**
     * @param ActionSequenceTriggerObserver $actionSequenceTriggerObserver
     * @return void
     */
    public function deleting(ActionSequenceTriggerObserver $actionSequenceTriggerObserver)
    {
        foreach (RunningAction::where('action_sequence_trigger_id', $actionSequenceTriggerObserver->id)->get() as $ra) {
            $ra->delete();
        }
    }
}