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
     * and calculate average raw value per sensorreadingroup.
     * In case $return_total_stats is true, the total average and min/max
     * will be calculated instead of each sensorreadings group's average
     *
     *
     * @param $query
     * @param array $logical_sensor_ids
     * @param Carbon|null $time_from
     * @param Carbon|null $time_to
     * @param bool $return_total_max
     * @return mixed
     */
    public function getAvgByLogicalSensor($query, array $logical_sensor_ids,
                                          Carbon $time_from = null, Carbon $time_to = null,
                                          $return_total_max = false)
    {
        if ($return_total_max) {
            $sensor_readings = $query
                ->select(
                    DB::raw('avg(rawvalue) as avg_rawvalue, min(rawvalue) as min_rawvalue, max(rawvalue) as max_rawvalue, \'x\' as `x`')
                );
        }
        else {
            $sensor_readings = $query
                ->select(
                    DB::raw('id, sensorreadinggroup_id, avg(rawvalue) as avg_rawvalue, created_at')
                );
        }
        
        $sensor_readings = $sensor_readings->whereIn('logical_sensor_id', $logical_sensor_ids);


        if (!is_null($time_from) && !is_null($time_to)) {
            if ($time_from->gt($time_to)) {
                $sensor_readings = $sensor_readings->whereRaw(
                    '(HOUR(`created_at`) <= ? OR HOUR(`created_at`) >= ?)',
                    [$time_to->hour, $time_from->hour]
                );
            }
            else {
                $sensor_readings = $sensor_readings->whereRaw(
                    'HOUR(`created_at`) >= ? AND HOUR(`created_at`) <= ?',
                    [$time_from->hour, $time_to->hour]
                );
            }
        }
        elseif (!is_null($time_from) && is_null($time_to)) {
            $sensor_readings = $sensor_readings->whereRaw('HOUR(`created_at`) >= ?', [$time_from->hour]);
        }
        elseif (is_null($time_from) && !is_null($time_to)) {
            $sensor_readings = $sensor_readings->whereRaw('HOUR(`created_at`) <= ?', [$time_to->hour]);
        }

        if ($return_total_max) {
            $sensor_readings = $sensor_readings->groupBy('x');
        }
        else {
            $sensor_readings = $sensor_readings->groupBy('sensorreadinggroup_id')->orderBy('created_at');
        }

        return $sensor_readings;
    }

}