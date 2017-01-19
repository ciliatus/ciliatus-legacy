<?php


namespace App\Http\Transformers;
use Carbon\Carbon;

/**
 * Class CategoryTransformer
 * @package App\Http\Transformers
 */
class CategoryTransformer extends Transformer
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
            'icon'      => $item['value'],
            'timestamps' => $this->parseTimestamps($item)
        ];

        return $return;
    }
}