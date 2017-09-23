<?php

namespace App\Observers;


use App\ActionSequence;

class ActionSequenceObserver
{
    /**
     * @param  ActionSequence $actionSequence
     * @return void
     */
    public function deleting(ActionSequence $actionSequence)
    {
        $actionSequence->actions()->delete();
        $actionSequence->schedules()->delete();
        $actionSequence->triggers()->delete();
        $actionSequence->intentions()->delete();
    }
}