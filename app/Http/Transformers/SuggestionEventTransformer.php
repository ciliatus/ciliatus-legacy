<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 23.07.2016
 * Time: 15:18
 */

namespace App\Http\Transformers;


/**
 * Class SuggestionEventTransformer
 * @package App\Http\Transformers
 */
class SuggestionEventTransformer extends Transformer
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
            'timestamps' => $this->parseTimestamps($item)
        ];

        $return = $this->addCiliatusSpecificFields($return, $item);

        if (isset($item['belongsTo_object'])) {
            $transformerName = 'App\\Http\\Transformers\\' . $item['belongsTo_type'] . 'Transformer';
            $transformer = new $transformerName();
            $return['belongsTo_object'] = $transformer->transform($item['belongsTo_object']);
        }

        if (isset($item['violation_type'])) {
            $return['violation_type'] = $item['violation_type'];
        }

        return $return;
    }
}