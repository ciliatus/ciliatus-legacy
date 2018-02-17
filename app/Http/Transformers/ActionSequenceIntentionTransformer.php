<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 23.07.2016
 * Time: 15:18
 */

namespace App\Http\Transformers;

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
                'should_be_started' => isset($item['should_be_started']) ? $item['should_be_started'] : false,
            ],
            'timestamps' => $this->parseTimestamps($item, [
                'last_start_at' => 'last_start',
                'last_finished_at' => 'last_finished'
            ])
        ];

        $return = $this->addCiliatusSpecificFields($return, $item);

        return $return;
    }
}