<?php

namespace App\Repositories;

use App\ActionSequenceSchedule;

/**
 * Class ActionSequenceScheduleRepository
 * @package App\Repositories
 */
class ActionSequenceScheduleRepository extends Repository {

    /**
     * ActionSequenceScheduleRepository constructor.
     * @param null $scope
     */
    public function __construct(ActionSequenceSchedule $scope = null)
    {

        $this->scope = $scope ? : new ActionSequenceSchedule();

    }

    /**
     * @return ActionSequenceSchedule
     */
    public function show()
    {
        $this->scope->running = $this->scope->running();
        $this->scope->will_run_today = $this->scope->will_run_today();
        $this->scope->ran_today = $this->scope->ran_today();
        $this->scope->is_overdue = $this->scope->is_overdue(10);

        return $this->scope;
    }

}