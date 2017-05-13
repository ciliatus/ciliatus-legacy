<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 23.07.2016
 * Time: 15:18
 */

namespace App\Http\Transformers;

/**
 * Class GenericComponentTypeTransformer
 * @package App\Http\Transformers
 */
class GenericComponentTypeTransformer extends Transformer
{


    /**
     * @param $item
     * @return array
     */
    public function transform($item)
    {
        $return = [
            'id'    => $item['id'],
            'name_singular' => $item['name_singular'],
            'name_plural' => $item['name_plural'],
            'timestamps' => $this->parseTimestamps($item),
            'icon'          =>  isset($item['icon']) ? $item['icon'] : '',
            'url'           =>  isset($item['url'])? $item['url'] : ''
        ];

        if (isset($item['properties']) && is_array($item['properties'])) {
            foreach ($item['properties'] as $property) {
                $return['properties'][$property['name']] = $property['value'];
            }
        }

        return $return;
    }
}