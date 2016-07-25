<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
