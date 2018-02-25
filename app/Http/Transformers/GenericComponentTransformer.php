<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 23.07.2016
 * Time: 15:18
 */

namespace App\Http\Transformers;

/**
 * Class GenericComponentTransformer
 * @package App\Http\Transformers
 */
class GenericComponentTransformer extends Transformer
{
    /**
     * @param $item
     * @return array
     */
    public function transform($item)
    {
        $item = $this->parseBelongsTo($item);

        $return = [
            'id'    => $item['id'],
            'name'  => $item['name'],
            'state' => isset($item['state']) ? $item['state'] : '',
            'controlunit_id' => $item['controlunit_id'],
            'belongsTo_type' => $item['belongsTo_type'],
            'belongsTo_id' => $item['belongsTo_id'],
            'timestamps' => $this->parseTimestamps($item)
        ];

        $return = $this->addCiliatusSpecificFields($return, $item);

        if (isset($item['states']) && is_array($item['states'])) {
            foreach ($item['states'] as $state) {
                $return['states'][$state['name']] = $state['value'];
            }
        }

        if (isset($item['belongsTo'])) {
            $return['belongsTo'] = $item['belongsTo'];
        }

        return $return;
    }
}