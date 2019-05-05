<?php

namespace App\Repositories;

use App\LogicalSensor;
use App\Terrarium;
use Carbon\Carbon;

/**
 * Class TerrariumRepository
 * @package App\Repositories
 */
class TerrariumRepository extends Repository
{
    /**
     * TerrariumRepository constructor.
     * @param Terrarium $scope
     */
    public function __construct(Terrarium $scope = null)
    {

        $this->scope = $scope ? : new Terrarium();
        $this->addCiliatusSpecificFields();

    }

    /**
     * @param Carbon $history_to
     * @param null $history_minutes
     * @return Terrarium
     */
    public function show(Carbon $history_to = null, $history_minutes = null)
    {
        /**
         * @var Terrarium $terrarium
         */
        $terrarium = $this->scope;

        if (is_null($history_to) && isset($this->show_parameters['history_to'])) {
            $history_to = $this->show_parameters['history_to'];
        }

        if (is_null($history_minutes) && isset($this->show_parameters['history_minutes'])) {
            $history_minutes = $this->show_parameters['history_minutes'];
        }

        if ($history_minutes != 0) {
            foreach (LogicalSensor::types() as $type) {
                $field = $type . '_history';
                $terrarium->$field = $terrarium->getSensorreadingsByType($type, false, $history_to, $history_minutes, true);
            }
        }

        $terrarium->default_background_filepath = $terrarium->background_image_path();
        $terrarium->capabilities = $terrarium->capabilities();
        $terrarium->temperature_critical = !$terrarium->temperatureOk();
        $terrarium->humidity_critical = !$terrarium->humidityOk();
        $terrarium->heartbeat_critical = !$terrarium->heartbeatOk();
        $terrarium->cooked_temperature_celsius_age_minutes =
            is_null($terrarium->cooked_temperature_celsius_updated_at) ?
                null :
                Carbon::now()->diffInMinutes($terrarium->cooked_temperature_celsius_updated_at);
        $terrarium->cooked_humidity_percent_age_minutes =
            is_null($terrarium->cooked_humidity_percent_updated_at) ?
                null :
                Carbon::now()->diffInMinutes($terrarium->cooked_humidity_percent_updated_at);
        $terrarium->state_ok = $terrarium->stateOk();

        return $terrarium;
    }

}