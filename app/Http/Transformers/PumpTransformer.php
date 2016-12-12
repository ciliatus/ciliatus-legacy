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
            'valve_id' => isset($item['valve_id']) ? $item['valve_id'] : '',
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

        if (isset($item['valve'])) {
            $return['valve'] = $valveTransformer->transform($item['valve']);
        }

        return $return;
    }
}