<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 23.07.2016
 * Time: 15:18
 */

namespace App\Http\Transformers;


/**
 * Class CriticalStateTransformer
 * @package App\Http\Transformers
 */
class CriticalStateTransformer extends Transformer
{
    /**
     * @param $item
     * @return array
     */
    public function transform($item)
    {
        $return = [
            'id'    => $item['id'],
            'soft_state' => $item['is_soft_state'],
            'belongs' => [
                'type' => $item['belongsTo_type'],
                'id'   => $item['belongsTo_id']
            ],
            'timestamps' => $this->parseTimestamps($item, [
                'notifications_sent_at' => 'notifications_sent_at',
                'recovered_at' => 'recovered_at'
            ])
        ];

        $return = $this->addCiliatusSpecificFields($return, $item);

        if (isset($item['belongsTo_object'])) {
            $belongsTo_transformerName = 'App\\Http\\Transformers\\' . $item['belongsTo_type'] . 'Transformer';
            $belongsTo_transformer = new $belongsTo_transformerName();
            $return['belongs']['object'] = $belongsTo_transformer->transform($item['belongsTo_object']->toArray());
        }

        return $return;
    }
}