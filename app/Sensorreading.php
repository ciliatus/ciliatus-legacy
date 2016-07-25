<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Sensorreading
 * @package App
 */
class Sensorreading extends Model
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
    public function logical_sensor()
    {
        return $this->belongsTo('App\LogicalSensor');
    }
}
