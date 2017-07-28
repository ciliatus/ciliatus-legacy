<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 16.07.2017
 * Time: 14:10
 */

namespace App\Factories;


use App\CiliatusModel;
use App\Http\Transformers\GenericTransformer;
use App\Http\Transformers\Transformer;
use ErrorException;

/**
 * Class TransformerFactory
 * @package App\Factory
 */
class TransformerFactory extends Factory
{

    /**
     * @param CiliatusModel|null $object
     * @return Transformer
     * @throws ErrorException
     */
    public static function get(CiliatusModel $object)
    {
        if (is_null($object)) {
            throw new ErrorException('Received null-model');
        }

        $class_name_arr = explode('\\', get_class($object));
        $class_name = end($class_name_arr);
        $transformer_name = sprintf(
            'App\Http\Transformers\%sTransformer',
            $class_name
        );

        if (class_exists($transformer_name)) {
            return new $transformer_name();
        }

        return new GenericTransformer();
    }

}