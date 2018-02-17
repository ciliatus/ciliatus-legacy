<?php


namespace App\Http\Transformers;

/**
 * Class AnimalWeighingScheduleTransformer
 * @package App\Http\Transformers
 */
class AnimalWeighingSchedulePropertyTransformer extends Transformer
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
            'due_days' => isset($item['next_weighing_at_diff']) ? $item['next_weighing_at_diff'] : 0,
            'timestamps' => $this->parseTimestamps($item, [
                'next_weighing_at' => 'next'
            ])
        ];

        $return = $this->addCiliatusSpecificFields($return, $item);

        return $return;
    }
}