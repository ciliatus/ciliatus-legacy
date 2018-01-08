<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 23.07.2016
 * Time: 15:18
 */

namespace App\Http\Transformers;

/**
 * Class PumpTransformer
 * @package App\Http\Transformers
 */
class PumpTransformer extends Transformer
{


    /**
     * @param $item
     * @return array
     */
    public function transform($item)
    {
        $controlunitTransformer = new ControlunitTransformer();
        $valveTransformer = new ValveTransformer();
        $return = [
            'id'    => $item['id'],
            'name' => $item['name'],
            'controlunit_id' => isset($item['controlunit_id']) ? $item['controlunit_id'] : '',
            'timestamps' => $this->parseTimestamps($item)
        ];

        $return = $this->addCiliatusSpecificFields($return, $item);

        if (isset($item['controlunit'])) {
            $return['controlunit'] = $controlunitTransformer->transform($item['controlunit']);
        }

        if (isset($item['valves'])) {
            $return['valves'] = $valveTransformer->transformCollection($item['valves']);
        }

        return $return;
    }
}