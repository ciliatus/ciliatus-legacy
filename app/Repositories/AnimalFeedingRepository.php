<?php

namespace App\Repositories;

use App\Property;

/**
 * Class AnimalFeedingRepository
 * @package App\Repositories
 */
class AnimalFeedingRepository extends Repository {

    /**
     * AnimalFeedingRepository constructor.
     * @param Property $scope
     */
    public function __construct(Property $scope = null)
    {

        $this->scope = $scope ? : new Property();

    }

    /**
     * @return Property
     */
    public function show()
    {
        $animal_feeding = $this->scope;

        $animal_feeding->animal = $animal_feeding->belongsTo_object()->get()->first()->toArray();

        return $animal_feeding;
    }

}