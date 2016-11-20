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
        $actionSequenceScheduleTransformer = new ActionSequenceScheduleTransformer();

        $return = [
            'id'            =>  $item['id'],
            'name'          =>  $item['name'],
            'duration_minutes' =>  $item['duration_minutes'],
            'timestamps'    => [
                'created'       => $item['created_at'],
                'updated'       => $item['updated_at'],
            ]
        ];

        if (isset($item['schedules'])) {
            $return['schedules'] = $actionSequenceScheduleTransformer->transformCollection($item['schedules']);
        }

        return $return;
    }
}