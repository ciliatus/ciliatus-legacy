<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 23.07.2016
 * Time: 15:18
 */

namespace App\Http\Transformers;

/**
 * Class ValveTransformer
 * @package App\Http\Transformers
 */
class ValveTransformer extends Transformer
{


    /**
     * @param $item
     * @return array
     */
    public function transform($item)
    {
        $pumpTransformer = new PumpTransformer();
        $terrariumTransformer = new TerrariumTransformer();
        $controlunitsTransformer = new ControlunitTransformer();
        $return = [
            'id'    => $item['id'],
            'name' => $item['name'],
            'controlunit_id' => $item['controlunit_id'],
            'terrarium_id' => $item['terrarium_id'],
            'pump_id' => $item['pump_id'],
            'timestamps' => $this->parseTimestamps($item)
        ];

        $return = $this->addCiliatusSpecificFields($return, $item);

        if (isset($item['controlunit'])) {
            $return['controlunit'] = $controlunitsTransformer->transform($item['controlunit']);
        }

        if (isset($item['terrarium'])) {
            $return['terrarium'] = $terrariumTransformer->transform($item['terrarium']);
        }

        if (isset($item['pump'])) {
            $return['pump'] = $pumpTransformer->transform($item['pump']);
        }

        return $return;
    }
}