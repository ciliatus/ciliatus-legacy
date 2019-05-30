<?php

namespace App;

use App\Events\RoomDeleted;
use App\Events\RoomUpdated;
use App\Traits\HasSensors;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

/**
 * Class Room
 * @package App
 */
class Room extends CiliatusModel
{
    use Uuids, Notifiable, HasSensors;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $casts = [
        'notifications_enabled' =>  'boolean',
        'temperature_critical'  =>  'boolean',
        'humidity_critical'     =>  'boolean',
        'heartbeat_critical'    =>  'boolean'
    ];

    /**
     * @var array
     */
    protected $dates = [
        'cooked_temperature_celsius_age_minutes',
        'cooked_humidity_percent_age_minutes'
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'display_name'
    ];

    /**
     * @var array
     */
    protected $dispatchesEvents = [
        'updated' => RoomUpdated::class,
        'deleting' => RoomDeleted::class
    ];

    /**
     * @return HasMany
     */
    public function terraria()
    {
        return $this->hasMany('App\Terrarium');
    }

    /**
     * @return mixed
     */
    public function properties()
    {
        return $this->hasMany('App\Property', 'belongsTo_id')
            ->where('belongsTo_type', 'Room');
    }


    /**
     * @return mixed
     */
    public function background_image_path()
    {
        $file_id = null;
        $prop = $this->property('generic', 'background_file_id');
        if (!is_null($prop)) {
            $file_id = $prop->value;
        }

        if (is_null($file_id)) {
            return null;
        }

        /**
         * @var File $file
         */
        $file = File::find($file_id);
        if (is_null($file)) {
            return null;
        }

        if ($thumb = $file->thumb()) {
            return $thumb->path_external();
        }

        \Log::warning('Using non-thumb as Room background. Room: ' . $this->id . ', File: ' . $file->id);
        return $file->path_external();
    }

    /**
     * @return string
     */
    public function icon()
    {
        return 'map-marker';
    }

    /**
     * @return string
     */
    public function url()
    {
        return url('rooms/' . $this->id);
    }
}
