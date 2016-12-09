<?php

namespace App\Repositories;

use App\Animal;
use App\Property;
use Carbon\Carbon;

/**
 * Class AnimalWeighingScheduleRepository
 * @package App\Repositories
 */
class AnimalWeighingScheduleRepository extends Repository {

    protected $scope;

    /**
     * AnimalWeighingScheduleRepository constructor.
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
        $last_weighing_of_type = $animal->last_weighing($fs->name);
        if (!is_null($last_weighing_of_type)) {
            $last_weighing_at = $last_weighing_of_type->created_at;
            $last_weighing_at->hour = 0;
            $last_weighing_at->minute = 0;
            $last_weighing_at->second = 0;

            $next_weighing_at = $last_weighing_at->addDays((int)$fs->value);

            $now = Carbon::now();
            $now->hour = 0;
            $now->minute = 0;
            $now->second = 0;

            $fs->next_weighing_at = $next_weighing_at->format('Y-m-d');
            $fs->next_weighing_at_diff = $now->diffInDays($next_weighing_at, false);
        }
        else {
            $fs->next_weighing_at = Carbon::now()->format('Y-m-d');
            $fs->next_weighing_at_diff = 0;
        }
        return $fs;
    }

}