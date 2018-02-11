<?php


namespace App\Http\Transformers;

/**
 * Class ReminderTransformer
 * @package App\Http\Transformers
 */
class ReminderTransformer extends Transformer
{
    /**
     * @param $item
     * @return array
     */
    public function transform($item)
    {
        $return = [
            'id'    => $item['id'],
            'name'  => $item['name'],
            'value'  => $item['value'],
            'timestamps' => $this->parseTimestamps($item)
        ];

        $return = $this->addCiliatusSpecificFields($return, $item);

        return $return;
    }
}