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
            'logical_sensor_id' => $item['logical_sensor_id'],
            'rawvalue_lowerlimit' => $item['rawvalue_lowerlimit'],
            'rawvalue_upperlimit' => $item['rawvalue_upperlimit'],
            'timestamps' => [
                'created' => $item['created_at'],
                'updated' => $item['updated_at'],
                'starts' => $item['starts_at']
            ]
        ];

        return $return;
    }
}