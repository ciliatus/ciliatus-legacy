<?php


namespace App\Http\Transformers;
use Carbon\Carbon;

/**
 * Class AnimalWeighingScheduleTransformer
 * @package App\Http\Transformers
 */
class AnimalWeighingScheduleTransformer extends Transformer
{


    /**
     * @param $item
     * @return array
     */
    public function transform($item)
    {
        $return = [
            'id'    => $item['id'],
            'type'  => $item['name'],
            'interval_days'  => $item['value'],
            'animal' => (new AnimalTransformer())->transform($item['animal']),
            'due_days' => isset($item['next_feeding_at_diff']) ? $item['next_feeding_at_diff'] : 0,
            'timestamps' => [
                'created' => $item['created_at'],
                'updated' => $item['updated_at'],
                'next'    => isset($item['next_feeding_at']) ? $item['next_feeding_at'] : null
            ],
            'icon'          =>  isset($item['icon']) ? $item['icon'] : '',
            'url'           =>  isset($item['url'])? $item['url'] : ''
        ];

        return $return;
    }
}