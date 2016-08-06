<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 23.07.2016
 * Time: 15:18
 */

namespace App\Http\Transformers;

/**
 * Class FileTransformer
 * @package App\Http\Transformers
 */
class FileTransformer extends Transformer
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

        if (isset($item['properties']) && is_array($item['properties'])) {
            foreach ($item['properties'] as $property) {
                $return['properties'][$property->name] = $property->value;
            }
        }

        return $return;
    }
}