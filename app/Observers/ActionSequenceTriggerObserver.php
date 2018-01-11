<?php

namespace App\Observers;

use App\ActionSequenceTrigger;
use App\RunningAction;

class ActionSequenceTriggerObserver
{

    /**
     * @param ActionSequenceTrigger $actionSequenceTrigger
     * @return void
     */
    public function deleting(ActionSequenceTrigger $actionSequenceTrigger)
    {
        foreach (RunningAction::where('action_sequence_trigger_id', $actionSequenceTrigger->id)->get() as $ra) {
            $ra->delete();
        }
    }
}