<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 23.07.2016
 * Time: 15:18
 */

namespace App\Http\Transformers;

/**
 * Class GenericTransformer
 * @package App\Http\Transformers
 */
class GenericTransformer extends Transformer
{
    /**
     * @param $item
     * @return array
     */
    public function transform($item)
    {
        if (!is_array($item)) {
            $item = $item->toArray();
        }
        $return = [
            'id'    =>  $item['id'],
            'name'  =>  $item['name'],
            'timestamps' => $this->parseTimestamps($item)
        ];

        $return = $this->addCiliatusSpecificFields($return, $item);

        return $return;
    }
}