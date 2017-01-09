<?php


namespace App\Http\Transformers;
use Carbon\Carbon;

/**
 * Class BiographyEntryTransformer
 * @package App\Http\Transformers
 */
class BiographyEntryTransformer extends Transformer
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
            'text'  => $item['value'],
            'timestamps' => $this->parseTimestamps($item),
            'icon'          =>  isset($item['icon']) ? $item['icon'] : '',
            'url'           =>  isset($item['url']) ? $item['url'] : ''
        ];

        if (isset($item['category'])) {
            $return['category'] = (new CategoryTransformer())->transform($item['category']);
        }

        return $return;
    }
}