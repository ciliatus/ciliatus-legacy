<?php

namespace App;

use App\Http\Transformers\TerrariumTransformer;
use App\Repositories\SensorreadingRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Terrarium
 * @package App
 */
class Terrarium extends CiliatusModel
{
    use Traits\Uuids;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */

    public $incrementing = false;

    /**
     * @var array
     */
    protected  $casts = [
        'notifications_enabled' =>  'boolean'
    ];

    /**
     * @param array $attributes
     * @return CiliatusModel|Terrarium
     */
    public static function create(array $attributes = [])
    {
        $new = parent::create($attributes);
        Log::create([
            'target_type'   =>  explode('\\', get_class($new))[count(explode('\\', get_class($new)))-1],
            'target_id'     =>  $new->id,
            'action'        => 'create'
        ]);

        return $new;
    }

    /**
     *
     */
    public function delete()
    {
        Log::create([
            'target_type'   =>  explode('\\', get_class($this))[count(explode('\\', get_class($this)))-1],
            'target_id'     =>  $this->id,
            'action'        => 'delete'
        ]);

        parent::delete();
    }

    /**
     * @return mixed
     */
    public function physical_sensors()
    {
        return $this->hasMany('App\PhysicalSensor', 'belongsTo_id')->with('logical_sensors')->where('belongsTo_type', 'terrarium');
    }

    /**
     * @return mixed
     */
    public function logical_sensors()
    {
        return $this->hasManyThrough('App\LogicalSensor', 'App\PhysicalSensor', 'belongsTo_id')->where('belongsTo_type', 'terrarium');
    }

    public function valves()
    {
        return $this->hasMany('App\Valve');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function animals()
    {
        return $this->hasMany('App\Animal');
    }

    /**
     * @return mixed
     */
    public function files()
    {
        return $this->hasMany('App\File', 'belongsTo_id')->where('belongsTo_type', 'Terrarium');
    }

    public function action_sequences()
    {
        return $this->hasMany('App\ActionSequence');
    }

    /**
     * @return float|int
     */
    public function getCurrentTemperature()
    {
        return round($this->fetchCurrentSensorreading('temperature_celsius'), 1);
    }

    /**
     * @return int
     */
    public function getCurrentHumidity()
    {
        return (int)$this->fetchCurrentSensorreading('humidity_percent');
    }

    /**
     * @return bool
     */
    public function temperatureOk()
    {
        foreach ($this->logical_sensors()->where('type', 'temperature_celsius')->get() as $ls) {
            if (!$ls->stateOk())
                return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function humidityOk()
    {
        foreach ($this->logical_sensors()->where('type', 'humidity_percent')->get() as $ls) {
            if (!$ls->stateOk())
                return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function stateOk()
    {
        return ($this->humidityOk() && $this->temperatureOk() && $this->heartbeatOk());
    }

    /**
     * @param int $minutes
     * @param null $from
     * @return mixed
     */
    public function getSensorReadingsTemperature($minutes = 120, $to = null)
    {
        return $this->fetchSensorreadings('temperature_celsius', $minutes, $to);
    }


    /**
     * @param int $minutes
     * @param null $from
     * @return mixed
     */
    public function getSensorReadingsHumidity($minutes = 120, $to = null)
    {
        return $this->fetchSensorreadings('humidity_percent', $minutes, $to);
    }

    /**
     * @return bool
     */
    public function heartbeatOk()
    {
        foreach ($this->physical_sensors as $ps) {
            if ($ps->heartbeatOk() !== true)
                return false;

            if (!is_null($ps->controlunit)) {
                if ($ps->controlunit->heartbeatOk() !== true)
                    return false;
            }
        }

        return true;
    }


    /**
     * @param $type
     * @param null $minutes
     * @param Carbon $to
     * @return array|static[]
     */
    private function fetchSensorreadings($type, $minutes = null, Carbon $to = null)
    {
        $logical_sensor_ids = [];

        /*
         * Fetch all logical sensors
         * of this terrarium with matching type
         */
        foreach ($this->physical_sensors as $ps) {
            foreach ($ps->logical_sensors()->where('type', $type)->get() as $ls) {
                $logical_sensor_ids[] = $ls->id;
            }
        }

        $sensor_readings = (new SensorreadingRepository())->getAvgByLogicalSensor($logical_sensor_ids, $minutes, $to);

        return $sensor_readings->get();

    }

    /**
     * @param $type
     * @return float|null
     */
    private function fetchCurrentSensorreading($type)
    {
        $avg = 0;
        $count = 0;

        foreach ($this->physical_sensors as $ps) {
            foreach ($ps->logical_sensors()->where('type', $type)->get() as $ls) {
                $avg += $ls->getCurrentCookedValue();
                $count++;
            }
        }

        if ($count > 0)
            return round($avg / $count, 1);

        return null;
    }

    /**
     * @return bool
     */
    public function check_notifications_enabled()
    {
        return $this->notifications_enabled;
    }

    /**
     * @return string
     */
    public function icon()
    {
        return 'video_label';
    }

    /**
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function url()
    {
        return url('terraria/' . $this->id);
    }
}
