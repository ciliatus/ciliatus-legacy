<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 23.07.2016
 * Time: 15:18
 */

namespace App\Http\Transformers;


/**
 * Class LogicalSensorThresholdTransformer
 * @package App\Http\Transformers
 */
class LogicalSensorThresholdTransformer extends Transformer
{
    /**
     * @param $item
     * @return array
     */
    public function transform($item)
    {
        $return = [
            'id'    => $item['id'],
            'name'  => $item['name'],
            'active'=> $item['active'],
            'logical_sensor_id' => $item['logical_sensor_id'],
            'adjusted_value_lowerlimit' => $item['adjusted_value_lowerlimit'],
            'adjusted_value_upperlimit' => $item['adjusted_value_upperlimit'],
            'timestamps' => $this->parseTimestamps($item, [
                'starts_at' => 'starts'
            ])
        ];

        $return = $this->addCiliatusSpecificFields($return, $item);

        return $return;
    }
}