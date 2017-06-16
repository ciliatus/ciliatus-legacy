<?php

namespace App\Traits;


use App\Action;
use App\ActionSequence;

/**
 * Class Components
 * @package App\Traits
 */
trait Components
{

    /**
     * @param $duration_minutes
     * @param string $desired_state
     * @return mixed
     */
    public function generateAction($duration_minutes, $desired_state = 'running')
    {
        $action = Action::create([
            'target_type' => explode("\\",__CLASS__)[1],
            'target_id' => $this->id,
            'desired_state' => $desired_state,
            'duration_minutes' => $duration_minutes
        ]);

        return $action;
    }

    /**
     * @param $duration_minutes
     * @param string $desired_state
     * @param ActionSequence $action_sequence
     * @return mixed
     */
    public function generateActionForSequence($duration_minutes, $desired_state = 'running', ActionSequence $action_sequence)
    {
        $action = $this->generateAction($duration_minutes, $desired_state);
        $action->action_sequence_id = $action_sequence->id;

        return $action;
    }

    /**
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reminders()
    {
        return $this->hasMany('App\ReminderEvent', 'belongsTo_id');
    }

}