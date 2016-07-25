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
        $return = [
            'id'    => $item['id'],
            'name' => $item['name'],
            'controlunit_id' => $item['controlunit_id'],
            'terrarium_id' => $item['terrarium_id'],
            'pump_id' => $item['pump_id'],
            'timestamps' => [
                'created' => $item['created_at'],
                'updated' => $item['updated_at'],
            ]
        ];

        if (isset($item['controlunit'])) {
            $return['controlunit'] = $item['controlunit'];
        }

        if (isset($item['terrarium'])) {
            $return['terrarium'] = $item['terrarium'];
        }

        if (isset($item['valve'])) {
            $return['valve'] = $item['valve'];
        }

        return $return;
    }
}