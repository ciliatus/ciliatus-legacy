<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 23.07.2016
 * Time: 15:18
 */

namespace App\Http\Transformers;

/**
 * Class LogTransformer
 * @package App\Http\Transformers
 */
class LogTransformer extends Transformer
{


    /**
     * @param $item
     * @return array
     */
    public function transform($item)
    {
        $return = [
            'id'    =>  $item['id'],
            'type'  =>  $item['type'],
            'action'=>  $item['action'],
            'description'   =>  $item['description'],
            'source_type' => $item['source_type'],
            'source'    => isset($item['source']) ? $item['source'] : null,
            'target_type' => $item['target_type'],
            'target'    => isset($item['target']) ? $item['target'] : null,
            'associated_type' => $item['associatedWith_type'],
            'associated'    => isset($item['associated']) ? $item['associated'] : null,
            'timestamps' => [
                'created' => $item['created_at'],
                'updated' => $item['updated_at'],
            ],
            'icon'          =>  isset($item['icon']) ? $item['icon'] : '',
            'url'           =>  isset($item['url'])? $item['url'] : ''
        ];



        return $return;
    }
}