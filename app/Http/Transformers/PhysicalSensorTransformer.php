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
        $controlunitTransformer = new ControlunitTransformer();
        $logicalSensorTransformer = new LogicalSensorTransformer();

        $return = [
            'id'    => $item['id'],
            'controlunit_id'  => $item['controlunit_id'],
            'name' => $item['name'],
            'timestamps' => [
                'created' => $item['created_at'],
                'updated' => $item['updated_at'],
            ],
            'icon'          =>  isset($item['icon']) ? $item['icon'] : '',
            'url'           =>  isset($item['url'])? $item['url'] : ''
        ];

        if (isset($item['controlunit'])) {
            $return['controlunit'] = $controlunitTransformer->transform($item['controlunit']);
        }

        if (isset($item['logical_sensors'])) {
            $return['logical_sensors'] = $logicalSensorTransformer->transformCollection($item['logical_sensors']);
        }

        return $return;
    }
}