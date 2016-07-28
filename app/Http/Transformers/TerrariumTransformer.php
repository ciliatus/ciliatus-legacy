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
        $return = [
            'id'    => $item['id'],
            'name'  => $item['name'],
            'friendly_name' => $item['friendly_name'],
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

        if (isset($item['history_combined'])) {
            $return['history_combined'] = $item['history_combined'];
        }



        return $return;
    }
}