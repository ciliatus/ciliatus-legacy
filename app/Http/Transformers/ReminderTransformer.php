<?php


namespace App\Http\Transformers;
use Carbon\Carbon;

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
            'timestamps' => $this->parseTimestamps($item),
            'icon'          =>  isset($item['icon']) ? $item['icon'] : '',
            'url'           =>  isset($item['url']) ? $item['url'] : ''
        ];

        return $return;
    }
}