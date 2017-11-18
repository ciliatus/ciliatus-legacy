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
            'state' => $item['state'],
            'type'  => isset($item['type']) ? $item['type'] : $item['generic_component_type_id'],
            'controlunit_id' => $item['controlunit_id'],
            'belongsTo_type' => $item['belongsTo_type'],
            'belongsTo_id' => $item['belongsTo_id'],
            'timestamps' => $this->parseTimestamps($item)
        ];

        $return = $this->addCiliatusSpecificFields($return, $item, []);

        if (isset($item['component_properties']) && is_array($item['component_properties'])) {
            foreach ($item['component_properties'] as $property) {
                $return['component_properties'][$property['name']] = $property['value'];
            }
        }

        if (isset($item['states']) && is_array($item['states'])) {
            foreach ($item['states'] as $state) {
                $return['states'][$state['name']] = $state['value'];
            }
        }

        if (isset($item['controlunit'])) {
            $return['controlunit'] = (new ControlunitTransformer())->transform($item['controlunit']);
        }

        if (isset($item['belongsTo'])) {
            $return['belongsTo'] = $item['belongsTo'];
        }

        return $return;
    }
}