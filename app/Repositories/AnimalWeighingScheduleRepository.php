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
        $ws = $this->scope;
        $animal = $ws->belongsTo_object()->get()->first();
        $ws->animal = $animal->toArray();
        $last_weighing_of_type = $animal->last_weighing();
        if (!is_null($last_weighing_of_type)) {
            $last_weighing_at = $last_weighing_of_type->created_at;
            $last_weighing_at->hour = 0;
            $last_weighing_at->minute = 0;
            $last_weighing_at->second = 0;

            $next_weighing_at = $last_weighing_at->addDays((int)$ws->value);

            $now = Carbon::now();
            $now->hour = 0;
            $now->minute = 0;
            $now->second = 0;

            $ws->next_weighing_at = $next_weighing_at->format('Y-m-d');
            $ws->next_weighing_at_diff = $now->diffInDays($next_weighing_at, false);
        }
        else {
            $ws->next_weighing_at = Carbon::now()->format('Y-m-d');
            $ws->next_weighing_at_diff = 0;
        }
        return $ws;
    }

}