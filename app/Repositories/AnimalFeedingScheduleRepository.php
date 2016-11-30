<?php

namespace App\Repositories;

use App\Animal;
use App\Property;
use Carbon\Carbon;

/**
 * Class AnimalFeedingScheduleRepository
 * @package App\Repositories
 */
class AnimalFeedingScheduleRepository extends Repository {

    protected $scope;

    /**
     * AnimalFeedingScheduleRepository constructor.
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
        $fs = $this->scope;
        $animal = $fs->belongsTo_object()->get()->first();
        $fs->animal = $animal->toArray();
        $last_feeding_of_type = $animal->last_feeding($fs->name);
        if (!is_null($last_feeding_of_type)) {
            $last_feeding_at = $last_feeding_of_type->created_at;
            $last_feeding_at->hour = 0;
            $last_feeding_at->minute = 0;
            $last_feeding_at->second = 0;

            $next_feeding_at = $last_feeding_at->addDays((int)$fs->value);

            $fs->next_feeding_at = $next_feeding_at->format('Y-m-d');
            $fs->next_feeding_at_diff = Carbon::now()->diffInDays($next_feeding_at, false);
        }
        else {
            $fs->next_feeding_at = Carbon::now()->format('Y-m-d');
            $fs->next_feeding_at_diff = 0;
        }
        return $fs;
    }

}