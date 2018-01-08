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
     * @param ActionSequenceSchedule|null $scope
     */
    public function __construct(ActionSequenceSchedule $scope = null)
    {

        $this->scope = $scope ? : new ActionSequenceSchedule();
        $this->addCiliatusSpecificFields();

    }

    /**
     * @return ActionSequenceSchedule
     */
    public function show()
    {
        $this->scope->running = $this->scope->running();
        $this->scope->will_run_today = $this->scope->willRunToday();
        $this->scope->ran_today = $this->scope->ranToday();
        $this->scope->is_overdue = $this->scope->isOverdue(10);

        return $this->scope;
    }

}
