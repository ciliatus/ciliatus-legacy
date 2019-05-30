<?php

namespace App\Http\Transformers;


/**
 * Class RoomTransformer
 * @package App\Http\Transformers
 */
class RoomTransformer extends Transformer
{
    /**
     * @param $item
     * @return array
     */
    public function transform($item)
    {
        $return = [
            'id'    => $item['id'],
            'name'  => isset($item['name']) ? $item['name'] : '',
            'display_name'  => isset($item['display_name']) ? $item['display_name'] : '',
            'timestamps' => $this->parseTimestamps($item)
        ];

        $return = $this->addCiliatusSpecificFields($return, $item);
        $return = $this->addSensorSpecificFields($return, $item);

        return $return;
    }
}