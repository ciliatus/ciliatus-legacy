<?php


namespace App\Http\Transformers;
use Carbon\Carbon;

/**
 * Class AnimalFeedingTransformer
 * @package App\Http\Transformers
 */
class AnimalFeedingTransformer extends Transformer
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
            'animal' => (new AnimalTransformer())->transform($item['animal']),
            'timestamps' => [
                'created' => $item['created_at'],
                'updated' => $item['updated_at']
            ],
            'icon'          =>  isset($item['icon']) ? $item['icon'] : '',
            'url'           =>  isset($item['url'])? $item['url'] : ''
        ];

        return $return;
    }
}