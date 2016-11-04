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
                'will_run_today' => isset($item['will_run_today']) ? $item['will_run_today'] : false,
                'ran_today' => isset($item['ran_today']) ? $item['ran_today'] : false,
                'running' => isset($item['running']) ? $item['running'] : false,
                'is_overdue' => isset($item['is_overdue']) ? $item['is_overdue'] : false,
            ],
            'timestamps'    => [
                'starts'        => Carbon::parse($item['starts_at'])->format('H:i'),
                'last_start'    => isset($item['last_start_at']) ? $item['last_start_at'] : null,
                'last_finished' => isset($item['last_finished_at']) ? $item['last_finished_at'] : null,
                'created'       => $item['created_at'],
                'updated'       => $item['updated_at'],
            ]
        ];

        if (isset($item['sequence'])) {
            $return['sequence'] = $item['sequence'];
        }

        if (isset($item['terrarium'])) {
            $return['terrarium'] = $item['terrarium'];
        }

        if (isset($item['state_ok'])) {
            $return['state_ok'] = $item['state_ok'];
        }

        return $return;
    }
}