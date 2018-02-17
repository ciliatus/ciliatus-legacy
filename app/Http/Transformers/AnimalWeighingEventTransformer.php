<?php


namespace App\Http\Transformers;

/**
 * Class AnimalWeighingEventTransformer
 * @package App\Http\Transformers
 */
class AnimalWeighingEventTransformer extends Transformer
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