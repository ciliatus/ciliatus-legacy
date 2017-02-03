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
        $terrariumTransformer = new TerrariumTransformer();

        $return = [
            'id'            =>  $item['id'],
            'name'          =>  $item['name'],
            'duration_minutes' =>  $item['duration_minutes'],
            'timestamps'    => [
                'created'       => $item['created_at'],
                'updated'       => $item['updated_at'],
            ],
            'icon'          =>  isset($item['icon']) ? $item['icon'] : '',
            'url'           =>  isset($item['url'])? $item['url'] : ''
        ];

        if (isset($item['actions'])) {
            $return['actions'] = $actionTransformer->transformCollection($item['actions']);
        }

        if (isset($item['schedules'])) {
            $return['schedules'] = $actionSequenceScheduleTransformer->transformCollection($item['schedules']);
        }

        if (isset($item['triggers'])) {
            $return['triggers'] = $actionSequenceTriggerTransformer->transformCollection($item['triggers']);
        }

        if (isset($item['terrarium'])) {
            $return['terrarium'] = $terrariumTransformer->transform($item['terrarium']);
        }

        return $return;
    }
}