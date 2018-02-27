<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 23.07.2016
 * Time: 15:18
 */

namespace App\Http\Transformers;

/**
 * Class ActionSequenceScheduleTransformer
 * @package App\Http\Transformers
 */
class ActionSequenceScheduleTransformer extends Transformer
{
    /**
     * @param $item
     * @return array
     */
    public function transform($item)
    {
        $return = [
            'id'            =>  $item['id'],
            'runonce'       =>  isset($item['runonce']) ? $item['runonce'] : false,
            'states'        => [
                'will_run_today' => isset($item['will_run_today']) ? $item['will_run_today'] : false,
                'ran_today' => isset($item['ran_today']) ? $item['ran_today'] : false,
                'running' => isset($item['running']) ? $item['running'] : false,
                'is_due' => isset($item['is_due']) ? $item['is_due'] : false,
                'is_overdue' => isset($item['is_overdue']) ? $item['is_overdue'] : false,
            ],
            'timestamps' => $this->parseTimestamps($item, [
                'starts_at' => 'starts',
                'last_start_at' => 'last_start',
                'last_finished_at' => 'last_finished'
            ])
        ];

        if (isset($item['sequence'])) {
            $return['sequence'] = is_array($item['sequence']) ? $item['sequence'] : $item['sequence']->transform();
        }

        $return = $this->addCiliatusSpecificFields($return, $item);

        if (isset($item['state_ok'])) {
            $return['state_ok'] = $item['state_ok'];
        }

        return $return;
    }
}