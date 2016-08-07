<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FileProperty
 * @package App
 */
class FileProperty extends CiliatusModel
{
    use Traits\Uuids;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */

    public $incrementing = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function file()
    {
        return $this->belongsTo('App\File');
    }

    /**
     * @return string
     */
    public function icon()
    {
        // TODO: Implement icon() method.
    }

    public function url()
    {
        // TODO: Implement url() method.
    }
}
