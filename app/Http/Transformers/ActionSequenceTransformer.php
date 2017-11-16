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
class ActionSequenceTransformer extends Transformer
{


    /**
     * @param $item
     * @return array
     */
    public function transform($item)
    {
        $actionTransformer = new ActionTransformer();
        $actionSequenceScheduleTransformer = new ActionSequenceScheduleTransformer();
        $actionSequenceTriggerTransformer = new ActionSequenceTriggerTransformer();
        $actionSequenceIntentionTransformer = new ActionSequenceIntentionTransformer();
        $terrariumTransformer = new TerrariumTransformer();

        $return = [
            'id'            =>  $item['id'],
            'name'          =>  $item['name'],
            'duration_minutes' =>  $item['duration_minutes'],
            'timestamps' => $this->parseTimestamps($item)
        ];

        $return = $this->addCiliatusSpecificFields($return, $item);

        if (isset($item['actions'])) {
            $return['actions'] = $actionTransformer->transformCollection($item['actions']);
        }

        if (isset($item['schedules'])) {
            $return['schedules'] = $actionSequenceScheduleTransformer->transformCollection($item['schedules']);
        }

        if (isset($item['triggers'])) {
            $return['triggers'] = $actionSequenceTriggerTransformer->transformCollection($item['triggers']);
        }

        if (isset($item['intentions'])) {
            $return['intentions'] = $actionSequenceIntentionTransformer->transformCollection($item['intentions']);
        }

        if (isset($item['terrarium'])) {
            $return['terrarium'] = $terrariumTransformer->transform($item['terrarium']);
        }

        return $return;
    }
}