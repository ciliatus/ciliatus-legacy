<?php


namespace App\Http\Transformers;
use Carbon\Carbon;

/**
 * Class AnimalCaresheetTransformer
 * @package App\Http\Transformers
 */
class AnimalCaresheetTransformer extends Transformer
{


    /**
     * @param $item
     * @return array
     */
    public function transform($item)
    {
        $return = [
            'id'    => $item['id'],
            'title'  => $item['name'],
            'timestamps' => $this->parseTimestamps($item),
            'icon'          =>  isset($item['icon']) ? $item['icon'] : '',
            'url'           =>  isset($item['url']) ? $item['url'] : ''
        ];

        return $return;
    }
}