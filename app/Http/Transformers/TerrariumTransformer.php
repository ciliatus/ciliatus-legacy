<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 23.07.2016
 * Time: 15:18
 */

namespace App\Http\Transformers;


/**
 * Class TerrariumTransformer
 * @package App\Http\Transformers
 */
class TerrariumTransformer extends Transformer
{


    /**
     * @param $item
     * @return array
     */
    public function transform($item)
    {
        $physicalSensorTransformer = new PhysicalSensorTransformer();
        $animalTransformer = new AnimalTransformer();
        $actionSequenceTransformer = new ActionSequenceTransformer();
        $return = [
            'id'    => $item['id'],
            'name'  => isset($item['name']) ? $item['name'] : '',
            'display_name' => $item['display_name'],
            'timestamps' => [
                'created' => $item['created_at'],
                'updated' => $item['updated_at'],
            ]
        ];

        if (isset($item['heartbeat_ok'])) {
            $return['heartbeat_ok'] = $item['heartbeat_ok'];
        }

        if (isset($item['physical_sensors'])) {
            $return['physical_sensors'] = $physicalSensorTransformer->transformCollection($item['physical_sensors']);
        }

        if (isset($item['animals'])) {
            $return['animals'] = $animalTransformer->transformCollection($item['animals']);
        }

        if (isset($item['action_sequences'])) {
            $return['action_sequences'] = $actionSequenceTransformer->transformCollection($item['action_sequences']);
        }

        if (isset($item['cooked_temperature_celsius'])) {
            $return['cooked_temperature_celsius'] = $item['cooked_temperature_celsius'];
        }

        if (isset($item['cooked_humidity_percent'])) {
            $return['cooked_humidity_percent'] = $item['cooked_humidity_percent'];
        }

        if (isset($item['temperature_history'])) {
            $return['temperature_history'] = $item['temperature_history'];
        }

        if (isset($item['humidity_history'])) {
            $return['humidity_history'] = $item['humidity_history'];
        }

        if (isset($item['temperature_trend'])) {
            $return['temperature_trend'] = $item['temperature_trend'];
        }

        if (isset($item['humidity_trend'])) {
            $return['humidity_trend'] = $item['humidity_trend'];
        }

        if (isset($item['temperature_ok'])) {
            $return['temperature_ok'] = $item['temperature_ok'];
        }

        if (isset($item['humidity_ok'])) {
            $return['humidity_ok'] = $item['humidity_ok'];
        }

        if (isset($item['state_ok'])) {
            $return['state_ok'] = $item['state_ok'];
        }

        if (isset($item['default_background_filepath'])) {
            $return['default_background_filepath'] = $item['default_background_filepath'];
        }

        return $return;
    }
}