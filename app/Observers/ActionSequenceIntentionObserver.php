<?php

namespace App\Observers;


use App\ActionSequenceIntention;
use App\RunningAction;

class ActionSequenceIntentionObserver
{
    /**
     * @param  ActionSequenceIntention $actionSequenceIntention
     * @return void
     */
    public function deleting(ActionSequenceIntention $actionSequenceIntention)
    {
        foreach (RunningAction::where('action_sequence_intention_id', $actionSequenceIntention->id)->get() as $ra) {
            $ra->delete();
        }
    }
}