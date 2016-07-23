<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 23.07.2016
 * Time: 15:18
 */

namespace App\Http\Transformers;

/**
 * Class AnimalTransformer
 * @package App\Http\Transformers
 */
class AnimalTransformer extends Transformer
{


    /**
     * @param $item
     * @return array
     */
    public function transform($item)
    {
        $return = [
            'id'    => $item['id'],
            'latin_name' => $item['lat_name'],
            'common_name' => $item['common_name'],
            'display_name' => $item['display_name']
        ];

        if (isset($item['terrarium_id'])) {
            $return['terrarium_id'] = $item['terrarium_id'];
        }

        return $return;
    }
}