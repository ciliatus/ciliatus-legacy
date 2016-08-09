<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 23.07.2016
 * Time: 15:18
 */

namespace App\Http\Transformers;

/**
 * Class UserTransformer
 * @package App\Http\Transformers
 */
class UserTransformer extends Transformer
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
            'timestamps' => [
                'created' => $item['created_at'],
                'updated' => $item['updated_at'],
            ]
        ];

        return $return;
    }
}