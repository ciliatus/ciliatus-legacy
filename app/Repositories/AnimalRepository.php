<?php

namespace App\Repositories;

use App\Animal;
use Cache;
use DB;

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

    }

    /**
     * @return Animal
     */
    public function show()
    {
        $animal = $this->scope;

        if (!is_null($animal->terrarium_id))
            $animal->terrarium_object = $animal->terrarium;

        $age = $animal->getAge();
        $animal->age_value = $age[0];
        $animal->age_unit = $age[1];
        $animal->gender_icon = $animal->gender_icon();
        $last_feeding = $animal->last_feeding();
        if (!is_null($last_feeding)) {
            $animal->last_feeding = $last_feeding;
        }

        $files = $animal->files()->with('properties')->get();
        $animal->default_background_filepath = null;
        foreach ($files as $f) {
            if ($f->property('is_default_background') == true) {
                $animal->default_background_filepath = $f->path_external();
                break;
            }
        }

        $animal->icon = $animal->icon();
        $animal->url = $animal->url();

        return $animal;
    }

}