<?php

namespace App;

use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Terrarium
 * @package App
 */
class Terrarium extends Model
{
    use Traits\Uuids;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */

    public $incrementing = false;

    /**
     * @return mixed
     */
    public function physical_sensors()
    {
        return $this->hasMany('App\PhysicalSensor', 'belongsTo_id')->where('belongsTo_type', 'terrarium')->with('logical_sensors');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function animals()
    {
        return $this->hasMany('App\Animal');
    }

    /**
     * @return float|int
     */
    public function getCurrentTemperature()
    {
        return round($this->fetchCurrentSensorreading('temperature_celsius'), 1);
    }

    /**
     * @return int
     */
    public function getCurrentHumidity()
    {
        return (int)$this->fetchCurrentSensorreading('humidity_percent');
    }

    /**
     * @param int $minutes
     * @return array|static[]
     */
    public function getSensorReadingsTemperature($minutes = 120)
    {
        return $this->fetchSensorreadings('temperature_celsius', $minutes);
    }

    /**
     * @param int $minutes
     * @return array|static[]
     */
    public function getSensorReadingsHumidity($minutes = 120)
    {
        return $this->fetchSensorreadings('humidity_percent', $minutes);
    }

    /**
     * @return string
     */
    public function getState()
    {
        return 'OK';
    }

    /**
     * @param $type
     * @param int $minutes
     * @return array|static[]
     */
    private function fetchSensorreadings($type, $minutes = 0)
    {

        $logical_sensor_ids = [];

        /*
         * Fetch all logical sensors
         * of this terrarium with matching type
         */
        foreach ($this->physical_sensors as $ps) {
            foreach ($ps->logical_sensors()->where('type', $type)->get() as $ls) {
                $logical_sensor_ids[] = $ls->id;
            }
        }

        /*
         * Select sensorreadings of the formerly
         * fetch logical sensor ids and calculate
         * average raw value per sensorreadingroup
         */
        $sensor_readings = DB::table('sensorreadings')
                                ->select(
                                    DB::raw('sensorreadinggroup_id, avg(rawvalue) as avg_rawvalue, created_at')
                                )
                                ->whereIn('logical_sensor_id', $logical_sensor_ids)
                                ->where('created_at', '>', Carbon::now()->addMinute(-$minutes))
                                ->orderBy('created_at')
                                ->groupBy('sensorreadinggroup_id')->get();

        return $sensor_readings;

    }

    /**
     * @param $type
     * @return float|null
     */
    private function fetchCurrentSensorreading($type)
    {
        $avg = 0;
        $count = 0;

        foreach ($this->physical_sensors as $ps) {
            foreach ($ps->logical_sensors()->where('type', $type)->get() as $ls) {
                $avg += $ls->getCurrentCookedValue();
                $count++;
            }
        }

        if ($count > 0)
            return round($avg / $count, 1);

        return null;
    }

}
