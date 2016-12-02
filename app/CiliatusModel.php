<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Animal
 * @package App
 */
abstract class CiliatusModel extends Model
{

    /**
     * @return string
     */
    abstract public function icon();

    /**
     * @return string
     */
    abstract public function url();
}
