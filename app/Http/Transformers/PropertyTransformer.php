<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 23.07.2016
 * Time: 15:18
 */

namespace App\Http\Transformers;

/**
 * Class PropertyTransformer
 * @package App\Http\Transformers
 */
class PropertyTransformer extends Transformer
{
    /**
     * @param $item
     * @return array
     */
    public function transform($item)
    {
        $return = [
            'id'    => $item['id'],
            'class' => 'Property',
            'type' => $item['type'],
            'name' => $item['name'],
            'value' => $item['value'],
            'belongsTo_type' => $item['belongsTo_type'],
            'belongsTo_id' => $item['belongsTo_id'],
            'timestamps' => $this->parseTimestamps($item)
        ];

        $return = $this->addCiliatusSpecificFields($return, $item);

        return $return;
    }
}