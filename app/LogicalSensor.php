<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class LogicalSensor
 * @package App
 */
class LogicalSensor extends Model
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
    public function physical_sensor()
    {
        return $this->belongsTo('App\PhysicalSensor');
    }
}
