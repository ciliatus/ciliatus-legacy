<?php

namespace App\Repositories;

use App\CiliatusModel;
use App\LogicalSensor;
use App\Factories\RepositoryFactory;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Illuminate\Support\Collection;

/**
 * Class Repository
 * @package App\Repositories
 */
abstract class Repository {

    /**
     * @var
     */
    protected $scope;

    /**
     * @var array
     */
    protected $show_parameters = [];

    /**
     * Adds properties, icon and url fields
     *
     * @param array $exclude
     */
    protected function addCiliatusSpecificFields(array $exclude = ['properties'])
    {
        if (!in_array('properties', $exclude))
            $this->scope->properties = $this->scope->properties()->get();

        if (!in_array('icon', $exclude))
            $this->scope->icon = $this->scope->icon();

        if (!in_array('api_base_url', $exclude))
            $this->scope->api_base_url = $this->scope->api_base_url();

        if (!in_array('web_base_url', $exclude))
            $this->scope->web_base_url = $this->scope->web_base_url();

        if (!in_array('url', $exclude))
            $this->scope->url = $this->scope->url();

        if (!in_array('active', $exclude))
            $this->scope->active = $this->scope->active();

        if (!in_array('class', $exclude)) {
            $class_split = explode("\\", get_class($this->scope));
            $this->scope->class = end($class_split);
        }

        $related_models = array_filter(
            $this->scope->getRelations(),
            function ($relation) {
                if (is_a($relation, MorphPivot::class)) {
                    return false;
                }

                return true;
            }
        );

        foreach ($related_models as $relation=>$objects) {
            if (is_a($objects, MorphPivot::class)) {
                unset($related_models[$relation]);
                dd($related_models);
                continue;
            }

            if (is_a($objects, Collection::class)) {
                foreach ($objects as $index=>$object) {
                    if (is_null($object)) {
                        unset($related_models[$relation][$index]);
                        continue;
                    }
                    $this->applyRepositoryToObject($object);
                }
            }
            else {
                $object = $objects;
                if (is_null($object)) {
                    unset($related_models[$relation]);
                    continue;
                }
                $this->applyRepositoryToObject($object);
            }
        }

        $this->scope->related_models = $related_models;
    }

    /**
     * @param $history_to
     * @param $history_minutes
     */
    protected function addSensorSpecificFields($history_to, $history_minutes)
    {
        if (is_null($history_to) && isset($this->show_parameters['history_to'])) {
            $history_to = $this->show_parameters['history_to'];
        }

        if (is_null($history_minutes) && isset($this->show_parameters['history_minutes'])) {
            $history_minutes = $this->show_parameters['history_minutes'];
        }

        if ($history_minutes != 0) {
            foreach (LogicalSensor::types() as $type) {
                $field = $type . '_history';
                $this->scope->$field = $this->scope->getSensorreadingsByType($type, false, $history_to, $history_minutes, true);
            }
        }

        $this->scope->temperature_critical = !$this->scope->temperatureOk();
        $this->scope->humidity_critical = !$this->scope->humidityOk();
        $this->scope->heartbeat_critical = !$this->scope->heartbeatOk();
        $this->scope->cooked_temperature_celsius_age_minutes =
            is_null($this->scope->cooked_temperature_celsius_updated_at) ?
                null :
                Carbon::now()->diffInMinutes($this->scope->cooked_temperature_celsius_updated_at);
        $this->scope->cooked_humidity_percent_age_minutes =
            is_null($this->scope->cooked_humidity_percent_updated_at) ?
                null :
                Carbon::now()->diffInMinutes($this->scope->cooked_humidity_percent_updated_at);
        $this->scope->state_ok = $this->scope->stateOk();
    }

    /**
     * @param CiliatusModel $object
     */
    protected function applyRepositoryToObject(CiliatusModel $object)
    {
        $repository = RepositoryFactory::get($object);
        $repository->show();
    }

    /**
     * Adds a parameter to be used when calling show method.
     * Useful when using a generic show() call (e.g. in
     * ApiController->respondTransformedAndPaginated())
     *
     * @param $name
     * @param $value
     */
    public function addShowParameter($name, $value)
    {
        $this->show_parameters[$name] = $value;
    }

}