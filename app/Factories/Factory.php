<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 16.07.2017
 * Time: 14:08
 */

namespace App\Factories;


use App\CiliatusModel;

abstract class Factory
{

    abstract static function get(CiliatusModel $object);

}