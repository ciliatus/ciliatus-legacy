<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 23.07.2016
 * Time: 15:16
 */

namespace App\Http\Transformers;


/**
 * Class Transformer
 * @package App\Http\Transformers
 */
abstract class Transformer
{

    /**
     * @param array $items
     * @return array
     */
    public function transformCollection(array $items)
    {
        return array_map([$this, 'transform'], $items);
    }

    /**
     * @param $item
     * @return mixed
     */
    public abstract function transform($item);

}