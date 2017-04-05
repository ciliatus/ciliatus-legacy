<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 23.07.2016
 * Time: 15:18
 */

namespace App\Http\Transformers;


/**
 * Class EventTransformer
 * @package App\Http\Transformers
 */
class EventTransformer extends Transformer
{


    /**
     * @param $item
     * @return array
     */
    public function transform($item)
    {
        $return = [
            'id'    => $item['id'],
            'belongsTo_type' => $item['belongsTo_type'],
            'belongsTo_id' => $item['belongsTo_id'],
            'name' => $item['name'],
            'value' => $item['value'],
            'value_json' => json_decode($item['value_json']),
            'timestamps' => $this->parseTimestamps($item),
            'icon'          =>  isset($item['icon']) ? $item['icon'] : '',
            'url'           =>  isset($item['url'])? $item['url'] : ''
        ];

        if (isset($item['belongsTo_object'])) {
            $transformerName = 'App\\Http\\Transformers\\' . $item['belongsTo_type'] . 'Transformer';
            $transformer = new $transformerName();
            $return['belongsTo_object'] = $transformer->transform($item['belongsTo_object']);
        }

        return $return;
    }
}