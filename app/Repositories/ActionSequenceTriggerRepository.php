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
     * @param null $scope
     */
    public function __construct(ActionSequenceTrigger $scope = null)
    {

        $this->scope = $scope ? : new ActionSequenceTrigger();

    }

    /**
     * @return ActionSequenceTrigger
     */
    public function show()
    {
        $this->scope->running = $this->scope->running();
        $this->scope->trigger_active = $this->scope->trigger_active();

        $this->scope->icon = $this->scope->icon();
        $this->scope->url = $this->scope->url();

        return $this->scope;
    }

}
