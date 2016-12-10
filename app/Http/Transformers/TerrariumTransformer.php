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
            'temperature_critical' => $item['temperature_critical'],
            'humidity_critical' => $item['humidity_critical'],
            'heartbeat_critical' => $item['heartbeat_critical'],
            'cooked_temperature_celsius' => $item['cooked_temperature_celsius'],
            'cooked_humidity_percent' => $item['cooked_humidity_percent'],
            'timestamps' => [
                'created' => $item['created_at'],
                'updated' => $item['updated_at'],
            ],
            'icon'          =>  isset($item['icon']) ? $item['icon'] : '',
            'url'           =>  isset($item['url'])? $item['url'] : ''
        ];

        if (isset($item['physical_sensors'])) {
            $return['physical_sensors'] = $physicalSensorTransformer->transformCollection($item['physical_sensors']);
        }

        if (isset($item['animals'])) {
            $return['animals'] = $animalTransformer->transformCollection($item['animals']);
        }

        if (isset($item['action_sequences'])) {
            $return['action_sequences'] = $actionSequenceTransformer->transformCollection($item['action_sequences']);
        }

        if (isset($item['temperature_history'])) {
            $return['temperature_history'] = $item['temperature_history'];
        }

        if (isset($item['humidity_history'])) {
            $return['humidity_history'] = $item['humidity_history'];
        }

        if (isset($item['default_background_filepath'])) {
            $return['default_background_filepath'] = $item['default_background_filepath'];
        }

        return $return;
    }
}