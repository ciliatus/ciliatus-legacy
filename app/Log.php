<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Log
 * @package App
 */
class Log extends Model
{
    use Traits\Uuids;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */

    public $incrementing = false;
}
