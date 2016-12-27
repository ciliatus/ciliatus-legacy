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
        $starts_at = Property::where('type', 'AnimalWeighingScheduleStartDate')->where('belongsTo_id', $ws->id)->orderBy('created_at', 'desc')->get()->first();


        /*
         * If there already was a weighing of this type
         * and the last weighing was after the schedule's starts_at date:
         *
         * Compare the schedule to the last weighing
         */
        if ((!is_null($last_weighing_of_type) && is_null($starts_at)) ||
            (!is_null($starts_at) && !is_null($last_weighing_of_type) && Carbon::parse($starts_at->value)->isSameDay($last_weighing_of_type->created_at))) {
            $last_weighing_at = $last_weighing_of_type->created_at;
            $last_weighing_at->hour = 0;
            $last_weighing_at->minute = 0;
            $last_weighing_at->second = 0;

            $next_weighing_at = (clone $last_weighing_at)->addDays((int)$ws->value);

            $now = Carbon::now();
            $now->hour = 0;
            $now->minute = 0;
            $now->second = 0;

            $ws->next_weighing_at = $next_weighing_at->format('Y-m-d');
            $ws->next_weighing_at_diff = $now->diffInDays($next_weighing_at, false);
        }
        else {
            /*
             * There was no last weighing or
             * there was no weighing since the starts_at value of the schedule:
             *
             * Compare to the starts_at date
             * if there is no starts_at: Compare to current date
             */
            $next_weighing_at = '';
            if (is_null($starts_at)) {
                $next_weighing_at = $ws->created_at->addDays((int)$ws->value);
                $ws->next_weighing_at = $next_weighing_at->format('Y-m-d');
            }
            else {
                $next_weighing_at = Carbon::parse($starts_at->value);
                $ws->next_weighing_at = $starts_at->value;
            }
            $ws->next_weighing_at_diff = Carbon::now()->diffInDays($next_weighing_at, false);
        }
        return $ws;
    }

}