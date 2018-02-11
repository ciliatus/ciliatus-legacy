<?php

namespace App\Observers;


use App\Action;

/**
 * Class ActionObserver
 * @package App\Observers
 */
class ActionObserver
{
    /**
     * @param Action $action
     * @return void
     */
    public function deleting(Action $action)
    {
        foreach ($action->running_actions as $ra) {
            $ra->delete();
        }
    }
}