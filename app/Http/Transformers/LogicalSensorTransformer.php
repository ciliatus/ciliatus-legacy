<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 23.07.2016
 * Time: 15:18
 */

namespace App\Http\Transformers;


/**
 * Class LogicalSensorTransformer
 * @package App\Http\Transformers
 */
class LogicalSensorTransformer extends Transformer
{


    /**
     * @param $item
     * @return array
     */
    public function transform($item)
    {
        $return = [
            'id'    => $item['id'],
            'physical_sensor_id' => $item['physical_sensor_id'],
            'type'  => $item['type'],
            'name' => $item['name'],
            'rawvalue' => $item['rawvalue'],
            'rawvalue_lowerlimit' => $item['rawvalue_lowerlimit'],
            'rawvalue_upperlimit' => $item['rawvalue_upperlimit'],
            'timestamps' => $this->parseTimestamps($item),
            'icon'          =>  isset($item['icon']) ? $item['icon'] : '',
            'url'           =>  isset($item['url'])? $item['url'] : ''
        ];

        if (isset($item['thresholds'])) {
            $return['thresholds'] = (
                new LogicalSensorThresholdTransformer()
            )->transformCollection($item['thresholds']);
        }

        if (isset($item['physical_sensor'])) {
            $return['physical_sensor'] = (
                new PhysicalSensorTransformer()
            )->transform($item['physical_sensor']);
        }


        if (isset($item['current_threshold_id'])) {
            $return['current_threshold_id'] = $item['current_threshold_id'];
        }

        $return = $this->addCiliatusSpecificFields($return, $item);

        return $return;
    }
}