<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 23.07.2016
 * Time: 15:18
 */

namespace App\Http\Transformers;
use Carbon\Carbon;

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
            'latin_name' => isset($item['lat_name']) ? $item['lat_name'] : '',
            'common_name' => isset($item['common_name']) ? $item['common_name'] : '',
            'display_name' => $item['display_name'],
            'birth_date' => !is_null($item['birth_date']) ? Carbon::parse($item['birth_date'])->format('d.m.Y') : null,
            'death_date' => !is_null($item['death_date']) ? Carbon::parse($item['death_date'])->format('d.m.Y') : null,
            'gender' => isset($item['gender']) ? $item['gender'] : '',
            'terrarium_id' => isset($item['terrarium_id']) ? $item['terrarium_id'] : '',
            'timestamps' => [
                'created' => $item['created_at'],
                'updated' => $item['updated_at'],
            ]
        ];

        if (isset($item['terrarium_object'])) {
            $return['terrarium'] = $item['terrarium_object'];
        }

        if (isset($item['age'])) {
            $return['age'] = $item['age'];
        }

        if (isset($item['gender_icon'])) {
            $return['gender_icon'] = $item['gender_icon'];
        }

        if (isset($item['default_background_filepath'])) {
            $return['default_background_filepath'] = $item['default_background_filepath'];
        }

        return $return;
    }
}