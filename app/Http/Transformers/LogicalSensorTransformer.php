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
        return [
            'id'    => $item['id'],
            'physical_sensor_id' => $item['physical_sensor_id'],
            'type'  => $item['type'],
            'name' => $item['name'],
            'rawvalue' => $item['rawvalue'],
            'rawvalue_lowerlimit' => $item['rawvalue_lowerlimit'],
            'rawvalue_upperlimit' => $item['rawvalue_upperlimit'],
        ];
    }
}