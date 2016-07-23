<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Animal
 * @package App
 */
class Animal extends Model
{
    use Traits\Uuids;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */

    public $incrementing = false;

    /**
     * @return mixed
     */
    public function terrarium()
    {
        return $this->belongsTo('App\Terrarium');
    }
}
