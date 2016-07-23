<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PhysicalSensor
 * @package App
 */
class PhysicalSensor extends Model
{
    use Traits\Uuids;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */

    public $incrementing = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|null
     */
    public function terrarium()
    {
        if ($this->belongsTo_type == 'terrarium')
            return $this->belongsTo('App\Terrarium', 'id', 'belongsTo_id');

        return null;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logical_sensors()
    {
        return $this->hasMany('App\LogicalSensor');
    }
}
