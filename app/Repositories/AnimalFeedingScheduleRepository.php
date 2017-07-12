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
     * @param Property|null $scope
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
        $belongsTo_object = $fs->belongsTo_object();
        $fs->animal = is_null($belongsTo_object) ? null : $fs->belongsTo_object()->toArray();
        $last_feeding_of_type = $belongsTo_object->last_feeding($fs->name);
        $starts_at = $fs->properties()->where('type', 'AnimalFeedingScheduleStartDate')
                         ->orderBy('created_at', 'desc')
                         ->get()->first();

        /*
         * If there already was a feeding of this type
         * and the last feeding was after the schedule's starts_at date:
         *
         * Compare the schedule to the last feeding
         */
        if ((!is_null($last_feeding_of_type) && is_null($starts_at)) ||
            (!is_null($last_feeding_of_type) && !is_null($starts_at) &&
                Carbon::parse($starts_at->value)->lte($last_feeding_of_type->created_at->addDays((int)$fs->value)))) {
            $last_feeding_at = $last_feeding_of_type->created_at;
            $last_feeding_at->hour = 0;
            $last_feeding_at->minute = 0;
            $last_feeding_at->second = 0;

            $next_feeding_at = (clone $last_feeding_at)->addDays((int)$fs->value);

            $now = Carbon::now();
            $now->hour = 0;
            $now->minute = 0;
            $now->second = 0;

            $fs->next_feeding_at = $next_feeding_at->format('Y-m-d');
            $fs->next_feeding_at_diff = $now->diffInDays($next_feeding_at, false);
        }
        else {
            /*
             * There was no last feeding or
             * there was no feeding since the starts_at value of the schedule:
             *
             * Compare to the starts_at date
             * if there is no starts_at: Compare to current date
             */
            $next_feeding_at = '';
            if (is_null($starts_at)) {
                $next_feeding_at = $fs->created_at->addDays((int)$fs->value);
                $fs->next_feeding_at = $next_feeding_at->format('Y-m-d');
            }
            else {
                $next_feeding_at = Carbon::parse($starts_at->value);
                $fs->next_feeding_at = $starts_at->value;
            }
            $fs->next_feeding_at_diff = Carbon::now()->diffInDays($next_feeding_at, false);
        }
        return $fs;
    }

}