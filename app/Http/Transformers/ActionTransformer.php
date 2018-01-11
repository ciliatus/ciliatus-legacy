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
            'timestamps' => $this->parseTimestamps($item)
        ];

        $return = $this->addCiliatusSpecificFields($return, $item);

        if (isset($item['target_object'])) {
            $transformerName = 'App\Http\Transformers\\' . $item['target_type'] . 'Transformer';
            $return['target_object'] = (new $transformerName())->transform($item['target_object']->toArray());
        }

        if (isset($item['wait_for_started_action_object'])) {
            $return['wait_for_started_action_object'] = $item['wait_for_started_action_object'];
        }

        if (isset($item['wait_for_finished_action_object'])) {
            $return['wait_for_finished_action_object'] = $item['wait_for_finished_action_object'];
        }

        if (isset($item['sequence'])) {
            $return['sequence'] = (new ActionSequenceTransformer())->transform($item['sequence']);
        }

        return $return;
    }
}