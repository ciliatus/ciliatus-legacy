<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 16.07.2017
 * Time: 14:08
 */

namespace App\Factories;


use App\CiliatusModel;

/**
 * Class Factory
 * @package App\Factories
 */
abstract class Factory
{

    /**
     * @param CiliatusModel $object
     * @return mixed
     */
    abstract static function get(CiliatusModel $object);

}