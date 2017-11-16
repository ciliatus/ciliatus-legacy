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
        $terrariumTransformer = new TerrariumTransformer();

        $return = [
            'id'    => $item['id'],
            'class' => 'PhysicalSensor',
            'controlunit_id'  => $item['controlunit_id'],
            'name' => $item['name'],
            'model' => $item['model'],
            'timestamps' => $this->parseTimestamps($item, ['heartbeat_at' => 'last_heartbeat'])
        ];

        $return = $this->addCiliatusSpecificFields($return, $item);

        if (isset($item['controlunit'])) {
            $return['controlunit'] = $controlunitTransformer->transform($item['controlunit']);
        }

        if (isset($item['terrarium'])) {
            $return['terrarium'] = $terrariumTransformer->transform($item['terrarium']);
        }

        if (isset($item['logical_sensors'])) {
            $return['logical_sensors'] = $logicalSensorTransformer->transformCollection($item['logical_sensors']);
        }

        return $return;
    }
}