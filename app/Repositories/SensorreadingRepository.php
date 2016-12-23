<?php

namespace App\Repositories;

use App\Terrarium;
use Carbon\Carbon;
use DB;
use App\Sensorreading;

/**
 * Class SensorreadingRepository
 * @package App\Repositories
 */
class SensorreadingRepository extends Repository {

    /**
     * SensorreadingRepository constructor.
     * @param null $scope
     */
    public function __construct($scope = null)
    {

        $this->scope = $scope ? : new Sensorreading();

    }

    /**
     * Select sensorreadings of the an array of logical sensors
     * and calculate average raw value per sensorreadingroup
     *
     * @param array $logical_sensor_ids
     * @param $minutes
     * @param $to
     * @return  \Illuminate\Database\Query\Builder
     */
    public function getAvgByLogicalSensor($query, array $logical_sensor_ids)
    {
        $sensor_readings = $query
            ->select(
                DB::raw('sensorreadinggroup_id, avg(rawvalue) as avg_rawvalue, created_at')
            )
            ->whereIn('logical_sensor_id', $logical_sensor_ids);

        $sensor_readings = $sensor_readings->groupBy('sensorreadinggroup_id')->orderBy('created_at');

        return $sensor_readings;
    }

}