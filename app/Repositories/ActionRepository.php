<?php

namespace App\Repositories;

use App\Action;

/**
 * Class ActionRepository
 * @package App\Repositories
 */
class ActionRepository extends Repository
{


    /**
     * ActionRepository constructor.
     * @param Action|null $scope
     */
    public function __construct(Action $scope = null)
    {

        $this->scope = $scope ? : new Action();
        $this->addCiliatusSpecificFields();

    }

    /**
     * @return Action
     */
    public function show()
    {
        $action = $this->scope;

        $action->target_object = $action->target_object();
        $action->wait_for_started_action_object = $action->wait_for_started_action_object();
        $action->wait_for_finished_action_object = $action->wait_for_finished_action_object();

        return $action;
    }

}