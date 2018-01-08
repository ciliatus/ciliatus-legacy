<?php

namespace App\Repositories;

use App\ActionSequenceTrigger;

/**
 * Class ActionSequenceTriggerRepository
 * @package App\Repositories
 */
class ActionSequenceTriggerRepository extends Repository {

    /**
     * ActionSequenceTriggerRepository constructor.
     * @param ActionSequenceTrigger|null $scope
     */
    public function __construct(ActionSequenceTrigger $scope = null)
    {

        $this->scope = $scope ? : new ActionSequenceTrigger();
        $this->addCiliatusSpecificFields();

    }

    /**
     * @return ActionSequenceTrigger
     */
    public function show()
    {
        $this->scope->running = $this->scope->running();
        $this->scope->should_be_started = $this->scope->shouldBeStarted();

        return $this->scope;
    }

}
