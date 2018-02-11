<?php


namespace App\Http\Transformers;

/**
 * Class AnimalFeedingSchedulePropertyTransformer
 * @package App\Http\Transformers
 */
class AnimalFeedingSchedulePropertyTransformer extends Transformer
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
            ])
        ];

        $return = $this->addCiliatusSpecificFields($return, $item);

        if (isset($item['animal'])) {
            $return['animal'] = (new AnimalTransformer())->transform($item['animal']);
        }

        return $return;
    }
}