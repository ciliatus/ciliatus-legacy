<?php

namespace App\Repositories;

use App\Event;

/**
 * Class AnimalWeighingRepository
 * @package App\Repositories
 */
class AnimalWeighingRepository extends Repository {

    /**
     * AnimalWeighingRepository constructor.
     * @param Event $scope
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
        $animal_weighing = $this->scope;

        $animal_weighing->animal = is_null($animal_weighing->belongsTo_object()) ? null : $animal_weighing->belongsTo_object()->toArray();

        return $animal_weighing;
    }

}