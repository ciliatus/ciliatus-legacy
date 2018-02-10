<?php

namespace App\Repositories;

use App\Animal;

/**
 * Class AnimalRepository
 * @package App\Repositories
 */
class AnimalRepository extends Repository
{


    /**
     * AnimalRepository constructor.
     * @param Animal|null $scope
     */
    public function __construct(Animal $scope = null)
    {

        $this->scope = $scope ? : new Animal();
        $this->addCiliatusSpecificFields();

    }

    /**
     * @return Animal
     */
    public function show()
    {
        $animal = $this->scope;

        $age = $animal->getAge();
        $animal->age_value = $age[0];
        $animal->age_unit = $age[1];
        $animal->gender_icon = $animal->gender_icon();

        $last_feeding = $animal->last_feeding();
        if (!is_null($last_feeding)) {
            $animal->last_feeding = $last_feeding;
        }

        $last_weighing = $animal->last_weighing();
        if (!is_null($last_weighing)) {
            $animal->last_weighing = $last_weighing;
            if ($animal->weighings->count() > 1) {
                $animal->last_weighing->trend = $animal->last_weighing()->trend(); #round(($animal->last_weighing->value - $animal->weighings->get(1)->value) / $animal->last_weighing->value * 100, 1);
            }
            else {
                $animal->last_weighing->trend = 0;
            }
        }

        $animal->default_background_filepath = $animal->background_image_path();

        return $animal;
    }

}