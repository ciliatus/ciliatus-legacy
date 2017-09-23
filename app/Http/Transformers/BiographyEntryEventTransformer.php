<?php


namespace App\Http\Transformers;
use Carbon\Carbon;

/**
 * Class BiographyEntryEventTransformer
 * @package App\Http\Transformers
 */
class BiographyEntryEventTransformer extends Transformer
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

        if (isset($item['files'])) {
            $return['files'] = (new FileTransformer())->transformCollection((is_array($item['files']) ? $item['files'] : $item['files']->toArray()));
        }

        return $return;
    }
}