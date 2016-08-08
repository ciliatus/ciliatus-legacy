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
            'belongs' => [
                'type' => $item['belongsTo_type'],
                'id'   => $item['belongsTo_id']
            ],
            'timestamps' => [
                'created' => $item['created_at'],
                'updated' => $item['updated_at'],
                'notifications_sent_at' => isset($item['notifications_sent_at']) ? $item['notifications_sent_at'] : null,
                'recovered_at' =>isset($item['recovered_at']) ? $item['recovered_at'] : null,
            ]
        ];

        return $return;
    }
}