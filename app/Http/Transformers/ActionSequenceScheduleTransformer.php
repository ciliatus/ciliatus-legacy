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
                'willRunToday' => isset($item['willRunToday']) ? $item['willRunToday'] : false,
                'ran_today' => isset($item['ran_today']) ? $item['ran_today'] : false,
                'running' => isset($item['running']) ? $item['running'] : false,
                'is_overdue' => isset($item['is_overdue']) ? $item['is_overdue'] : false,
            ],
            'timestamps' => $this->parseTimestamps($item, [
                'starts_at' => 'starts',
                'last_start_at' => 'last_start',
                'last_finished_at' => 'last_finished'
            ]),
            'icon'          =>  isset($item['icon']) ? $item['icon'] : '',
            'url'           =>  isset($item['url'])? $item['url'] : ''
        ];

        if (isset($item['sequence'])) {
            $return['sequence'] = (new ActionSequenceTransformer())->transform($item['sequence']);
        }

        if (isset($item['terrarium'])) {
            $return['terrarium'] = (new TerrariumTransformer())->transform($item['terrarium']);
        }

        if (isset($item['state_ok'])) {
            $return['state_ok'] = $item['state_ok'];
        }

        return $return;
    }
}