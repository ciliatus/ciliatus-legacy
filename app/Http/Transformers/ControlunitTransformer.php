<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 23.07.2016
 * Time: 15:18
 */

namespace App\Http\Transformers;

/**
 * Class ControlunitTransformer
 * @package App\Http\Transformers
 */
class ControlunitTransformer extends Transformer
{


    /**
     * @param $item
     * @return array
     */
    public function transform($item)
    {
        $return = [
            'id'    => $item['id'],
            'name' => $item['name'],
            'timestamps' => $this->parseTimestamps($item, ['heartbeat_at' => 'last_heartbeat']),
            'icon'          =>  isset($item['icon']) ? $item['icon'] : '',
            'url'           =>  isset($item['url'])? $item['url'] : ''
        ];

        if (isset($item['physical_sensors'])) {
            $return['physical_sensors'] = (new PhysicalSensorTransformer())->transformCollection($item['physical_sensors']);
        }

        if (isset($item['valves'])) {
            $return['valves'] = (new ValveTransformer())->transformCollection($item['valves']);
        }

        if (isset($item['pumps'])) {
            $return['pumps'] = (new PumpTransformer())->transformCollection($item['pumps']);
        }

        if (isset($item['generic_components'])) {
            $return['generic_components'] = (new GenericComponentTransformer())->transformCollection($item['generic_components']);
        }

        return $return;
    }
}