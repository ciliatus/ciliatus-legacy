<?php

namespace App\Repositories;

use App\Sensorreading;
use Carbon\Carbon;
use DB;

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
     * @param $query
     * @param array $logical_sensor_ids
     * @return mixed
     */
    public function getByLogicalSensor($query, array $logical_sensor_ids)
    {
        return $query->with('logical_sensor')
                     ->whereIn('logical_sensor_id', $logical_sensor_ids)->orderBy('read_at', 'desc');
    }

    /**
     * Select sensorreadings of the an array of logical sensors
     * and calculate average raw value per sensor reading group.
     * In case $return_total_stats is true, the total average and min/max
     * will be calculated instead of each sensorreadings group's average
     *
     *
     * @param $query
     * @param array $logical_sensor_ids
     * @param Carbon|null $time_of_day_from
     * @param Carbon|null $time_of_day_to
     * @param bool $return_total_max
     * @return mixed
     */
    public function getAvgByLogicalSensor($query, array $logical_sensor_ids,
                                          Carbon $time_of_day_from = null, Carbon $time_of_day_to = null,
                                          $return_total_max = false)
    {
        if ($return_total_max) {
            $sensor_readings = $query
                ->select(
                    DB::raw('avg(rawvalue) as avg_rawvalue, min(rawvalue) as min_rawvalue, max(rawvalue) as max_rawvalue, min(is_anomaly) as is_anomaly, \'x\' as `x`')
                );
        }
        else {
            $sensor_readings = $query
                ->select(
                    DB::raw('id, sensorreadinggroup_id, avg(rawvalue) as avg_rawvalue, min(is_anomaly) as anomaly, read_at')
                );
        }
        
        $sensor_readings = $sensor_readings->whereIn('logical_sensor_id', $logical_sensor_ids);


        if (!is_null($time_of_day_from) && !is_null($time_of_day_to)) {
            if ($time_of_day_from->gt($time_of_day_to)) {
                $sensor_readings = $sensor_readings->whereRaw(
                    '(HOUR(`read_at`) <= ? OR HOUR(`read_at`) >= ?)',
                    [$time_of_day_to->hour, $time_of_day_from->hour]
                );
            }
            else {
                $sensor_readings = $sensor_readings->whereRaw(
                    'HOUR(`read_at`) >= ? AND HOUR(`read_at`) <= ?',
                    [$time_of_day_from->hour, $time_of_day_to->hour]
                );
            }
        }
        elseif (!is_null($time_of_day_from) && is_null($time_of_day_to)) {
            $sensor_readings = $sensor_readings->whereRaw('HOUR(`read_at`) >= ?', [$time_of_day_from->hour]);
        }
        elseif (is_null($time_of_day_from) && !is_null($time_of_day_to)) {
            $sensor_readings = $sensor_readings->whereRaw('HOUR(`read_at`) <= ?', [$time_of_day_to->hour]);
        }

        if ($return_total_max) {
            $sensor_readings = $sensor_readings->groupBy('x');
        }
        else {
            $sensor_readings = $sensor_readings->groupBy('sensorreadinggroup_id')->orderBy('read_at');
        }

        return $sensor_readings;
    }


    /**
     * @return Sensorreading
     */
    public function show()
    {
        $sr = $this->scope;

        return $sr;
    }

}