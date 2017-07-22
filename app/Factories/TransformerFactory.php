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

/**
 * Class TransformerFactory
 * @package App\Factory
 */
class TransformerFactory extends Factory
{

    /**
     * @param CiliatusModel|null $object
     * @return Transformer
     */
    public static function get(CiliatusModel $object = null)
    {
        if (is_null($object)) {
            \Log::warning('Received null-model, returning GenericTransformer', debug_backtrace());
            return new GenericTransformer($object);
        }

        $class_name_arr = explode('\\', get_class($object));
        $class_name = end($class_name_arr);
        $transformer_name = sprintf(
            'App\Http\Transformers\%sTransformer',
            $class_name
        );

        if (class_exists($transformer_name)) {
            return new $transformer_name($object);
        }

        \Log::debug('Returning generic transformer for class ' . $class_name);
        return new GenericTransformer($object);
    }

}