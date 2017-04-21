<?php

namespace App\Repositories;

use App\Event;
use App\Property;

/**
 * Class AnimalCaresheetRepository
 * @package App\Repositories
 */
class AnimalCaresheetRepository extends Repository {

    /**
     * AnimalCaresheetRepository constructor.
     * @param Event|null $scope
     */
    public function __construct(Event $scope = null)
    {

        $this->scope = $scope ? : new Event();

    }

    /**
     * @return Event
     */
    public function show()
    {
        $animal_caresheet = $this->scope;



        return $animal_caresheet;
    }

}