<?php

namespace App\Repositories;

use App\ActionSequence;

/**
 * Class ActionSequenceRepository
 * @package App\Repositories
 */
class ActionSequenceRepository extends Repository {

    /**
     * ActionSequenceRepository constructor.
     * @param null $scope
     */
    public function __construct(ActionSequence $scope = null)
    {

        $this->scope = $scope ? : new ActionSequence();

    }

    /**
     * @return ActionSequence
     */
    public function show()
    {
        foreach ($this->scope->schedules as &$s) {
            $s = (new ActionSequenceScheduleRepository($s))->show();
        }
        foreach ($this->scope->triggers as &$t) {
            $t = (new ActionSequenceTriggerRepository($t))->show();
        }
        foreach ($this->scope->intentions as &$i) {
            $i = (new ActionSequenceIntentionRepository($i))->show();
        }

        $this->scope->icon = $this->scope->icon();
        $this->scope->url = $this->scope->url();

        return $this->scope;
    }

}
