<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 23.07.2016
 * Time: 15:18
 */

namespace App\Http\Transformers;

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
        $return = [
            'id'    => $item['id'],
            'controlunit_id'  => $item['controlunit_id'],
            'name' => $item['name'],
            'model' => $item['model'],
            'timestamps' => $this->parseTimestamps($item, ['heartbeat_at' => 'last_heartbeat'])
        ];

        $return = $this->addCiliatusSpecificFields($return, $item);

        return $return;
    }
}