<?php


namespace App\Http\Transformers;

/**
 * Class AnimalFeedingEventTransformer
 * @package App\Http\Transformers
 */
class AnimalFeedingEventTransformer extends Transformer
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
            'amount'  => $item['value'],
            'timestamps' => $this->parseTimestamps($item)
        ];

        $return = $this->addCiliatusSpecificFields($return, $item);

        return $return;
    }
}