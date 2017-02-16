<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 23.07.2016
 * Time: 15:18
 */

namespace App\Http\Transformers;


/**
 * Class SensorreadingTransformer
 * @package App\Http\Transformers
 */
class SensorreadingTransformer extends Transformer
{

    /**
     * @param $item
     * @return array
     */
    public function transform($item)
    {
        $return = [
            'id'    => $item['id'],
            'class' => 'Sensorreading',
            'logical_sensor_id'  => $item['logical_sensor_id'],
            'group_id' => $item['sensorreadinggroup_id'],
            'rawvalue' => $item['rawvalue'],
            'timestamps' => [
                'created' => $item['created_at'],
                'updated' => $item['updated_at'],
            ],
            'icon'          =>  isset($item['icon']) ? $item['icon'] : '',
            'url'           =>  isset($item['url'])? $item['url'] : ''
        ];

        return $return;
    }
}