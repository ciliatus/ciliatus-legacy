<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 23.07.2016
 * Time: 15:18
 */

namespace App\Http\Transformers;

/**
 * Class ActionTransformer
 * @package App\Http\Transformers
 */
class ActionTransformer extends Transformer
{


    /**
     * @param $item
     * @return array
     */
    public function transform($item)
    {
        $return = [
            'id'            =>  $item['id'],
            'target_type'   =>  $item['target_type'],
            'target_id'     =>  $item['target_id'],
            'desired_state' =>  $item['desired_state'],
            'duration_minutes'=>$item['duration_minutes'],
            'wait_for_started_action_id' => $item['wait_for_started_action_id'],
            'wait_for_finished_action_id' => $item['wait_for_finished_action_id'],
            'timestamps'    => [
                'created'       => $item['created_at'],
                'updated'       => $item['updated_at'],
            ]
        ];

        if (isset($item['target_object'])) {
            $return['target_object'] = $item['target_object'];
        }

        if (isset($item['wait_for_started_action_object'])) {
            $return['wait_for_started_action_object'] = $item['wait_for_started_action_object'];
        }

        if (isset($item['wait_for_finished_action_object'])) {
            $return['wait_for_finished_action_object'] = $item['wait_for_finished_action_object'];
        }

        return $return;
    }
}