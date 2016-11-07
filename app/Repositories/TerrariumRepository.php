<?php

namespace App\Repositories;

use App\Terrarium;
use Cache;
use Carbon\Carbon;
use DB;
use App\Sensorreading;

/**
 * Class TerrariumRepository
 * @package App\Repositories
 */
class TerrariumRepository extends Repository {

    /**
     * TerrariumRepository constructor.
     * @param null $scope
     */
    public function __construct(Terrarium $scope = null)
    {

        $this->scope = $scope ? : new Terrarium();

    }

    public function show($history_to = null, $history_minutes = null)
    {
        if (is_null($history_to))
            $history_to = Carbon::now();

        if (is_null($history_minutes))
            $history_minutes = env('TERRARIUM_DEFAULT_HISTORY_MINUTES', 15);

        $terrarium = $this->scope;
        $id = $terrarium->id;
        $cache_key = 'api-show-terrarium-' . $id;
        if (Cache::has('api-show-terrarium-' . $id)) {
            //return Cache::get($cache_key);
        }

        /*
         * Add cooked values to terrarium object
         */
        $terrarium->cooked_humidity_percent = $terrarium->getCurrentHumidity();
        $terrarium->cooked_temperature_celsius = $terrarium->getCurrentTemperature();
        $terrarium->heartbeat_ok = $terrarium->heartbeatOk();

        /*
         * load temperature values and convert them to an array seperated by commata
         */
        $temperature_values = array_column($terrarium->getSensorReadingsTemperature($history_minutes, $history_to), 'avg_rawvalue');
        $terrarium->temperature_history = implode(',',
            array_map(
                function($val) {
                    return round($val, 1);
                },
                $temperature_values
            )
        );

        /*
         * load humidity values and convert them to an array seperated by commata
         */
        $humidity_values = array_column($terrarium->getSensorReadingsHumidity($history_minutes, $history_to), 'avg_rawvalue');
        $terrarium->humidity_history = implode(',',
            array_map(
                function($val) {
                    return round($val, 1);
                },
                $humidity_values
            )
        );

        /*
         * @TODO: Create better algorithm to calculate trends
         *
         * calculate trends
         * compares the first value of the last third and the last value
         * for lack of a better algorithm atm
         */
        if (count($temperature_values) > 0)
            $terrarium->temperature_trend = $temperature_values[count($temperature_values)-1] - $temperature_values[0];
        if (count($humidity_values) > 0)
            $terrarium->humidity_trend = $humidity_values[count($humidity_values)-1] - $humidity_values[0];

        $terrarium->humidity_ok = $terrarium->humidityOk();
        $terrarium->temperature_ok = $terrarium->temperatureOk();
        $terrarium->state_ok = $terrarium->stateOk();

        $terrarium->animals = $terrarium->animals()->get()->toArray();

        Cache::add($cache_key, $terrarium, env('CACHE_API_TERRARIUM_SHOW_DURATION') / 60);

        return $terrarium;
    }

}