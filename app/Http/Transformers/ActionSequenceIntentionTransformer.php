<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 23.07.2016
 * Time: 15:18
 */

namespace App\Http\Transformers;
use Carbon\Carbon;

/**
 * Class ActionSequenceIntentionTransformer
 * @package App\Http\Transformers
 */
class ActionSequenceIntentionTransformer extends Transformer
{


    /**
     * @param $item
     * @return array
     */
    public function transform($item)
    {
        $return = [
            'id'                    =>  $item['id'],
            'action_sequence_id'    =>  $item['action_sequence_id'],
            'type'                  =>  $item['type'],
            'intention'             =>  $item['intention'],
            'minimum_timeout_minutes' => $item['minimum_timeout_minutes'],
            'timeframe' => [
                'start' => $item['timeframe_start'],
                'end'   => $item['timeframe_end'],
            ],
            'states'    => [
                'running'       => isset($item['running']) ? $item['running'] : false,
                'intention_active'=> isset($item['intention_active']) ? $item['intention_active'] : false,
            ],
            'timestamps'    => [
                'last_start'    => isset($item['last_start_at']) ? $item['last_start_at'] : null,
                'last_finished' => isset($item['last_finished_at']) ? $item['last_finished_at'] : null,
                'created'       => $item['created_at'],
                'updated'       => $item['updated_at'],
            ],
            'icon'          =>  isset($item['icon']) ? $item['icon'] : '',
            'url'           =>  isset($item['url'])? $item['url'] : ''
        ];

        if (isset($item['sequence'])) {
            $return['sequence'] = (new ActionSequenceTransformer())->transform($item['sequence']);
        }

        return $return;
    }
}