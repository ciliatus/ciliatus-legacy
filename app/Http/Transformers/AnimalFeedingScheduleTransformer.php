<?php


namespace App\Http\Transformers;
use Carbon\Carbon;

/**
 * Class AnimalFeedingScheduleTransformer
 * @package App\Http\Transformers
 */
class AnimalFeedingScheduleTransformer extends Transformer
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
            'due_days' => isset($item['next_feeding_at_diff']) ? $item['next_feeding_at_diff'] : 0,
            'timestamps' => $this->parseTimestamps($item, [
                'next_feeding_at' => 'next'
            ]),
            'icon'          =>  isset($item['icon']) ? $item['icon'] : '',
            'url'           =>  isset($item['url'])? $item['url'] : ''
        ];

        if (isset($item['animal'])) {
            $return['animal'] = (new AnimalTransformer())->transform($item['animal']);
        }

        return $return;
    }
}