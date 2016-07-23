<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 23.07.2016
 * Time: 15:18
 */

namespace App\Http\Transformers;

use App\Http\Transformers\LogicalSensorTransformer;

/**
 * Class PhysicalSensorTransformer
 * @package App\Http\Transformers
 */
class PhysicalSensorTransformer extends Transformer
{


    /**
     * @param $item
     * @return array
     */
    public function transform($item)
    {
        $logicalSensorTransformer = new LogicalSensorTransformer();

        $return = [
            'id'    => $item['id'],
            'controlunit_id'  => $item['controlunit_id'],
            'name' => $item['name'],
        ];

        if (isset($item['logical_sensors'])) {
            $return['logical_sensors'] = $logicalSensorTransformer->transformCollection($item['logical_sensors']);
        }

        return $return;
    }
}