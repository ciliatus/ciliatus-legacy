<?php

namespace App\Repositories;

use App\ReminderEvent;

/**
 * Class ReminderRepository
 * @package App\Repositories
 */
class ReminderRepository extends Repository {

    /**
     * ReminderRepository constructor.
     * @param ReminderEvent $scope
     */
    public function __construct(ReminderEvent $scope = null)
    {

        $this->scope = $scope ? : new ReminderEvent();

    }

    /**
     * @return ReminderEvent
     */
    public function show()
    {
        $reminder = $this->scope;

        return $reminder;
    }

}