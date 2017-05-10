<?php

namespace App;

use App\Events\LogicalSensorDeleted;
use App\Events\LogicalSensorUpdated;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LogicalSensor
 *
 * @package App
 * @property string $id
 * @property string $name
 * @property string $physical_sensor_id
 * @property string $type
 * @property float $rawvalue
 * @property float $rawvalue_lowerlimit
 * @property float $rawvalue_upperlimit
 * @property mixed $soft_state_duration_minutes
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\CriticalState[] $critical_states
 * @property-read \App\PhysicalSensor $physical_sensor
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Sensorreading[] $sensorreadings
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\LogicalSensorThreshold[] $thresholds
 * @method static \Illuminate\Database\Query\Builder|\App\LogicalSensor whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LogicalSensor whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LogicalSensor whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LogicalSensor wherePhysicalSensorId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LogicalSensor whereRawvalue($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LogicalSensor whereRawvalueLowerlimit($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LogicalSensor whereRawvalueUpperlimit($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LogicalSensor whereSoftStateDurationMinutes($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LogicalSensor whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LogicalSensor whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LogicalSensor extends CiliatusModel
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
    protected $fillable = ['name', 'physical_sensor_id'];

    /**
     *
     */
    public function delete()
    {
        $this->thresholds()->delete();
        $this->properties()->delete();

        broadcast(new LogicalSensorDeleted($this->id));

        parent::delete();
    }


    /**
     * @param array $options
     * @return bool
     */
    public function save(array $options = [])
    {
        $result = parent::save($options);

        if (!isset($options['silent'])) {
            broadcast(new LogicalSensorUpdated($this));
        }

        return $result;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function properties()
    {
        return $this->hasMany('App\Property', 'belongsTo_id')->where('belongsTo_type', 'LogicalSensor');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function physical_sensor()
    {
        return $this->belongsTo('App\PhysicalSensor')->with('terrarium', 'controlunit');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sensorreadings()
    {
        return $this->hasMany('App\Sensorreading', 'logical_sensor_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function critical_states()
    {
        return $this->hasMany('App\CriticalState', 'belongsTo_id')->where('belongsTo_type', 'LogicalSensor');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function thresholds()
    {
        return $this->hasMany('App\LogicalSensorThreshold');
    }

    /**
     * Adjust rawvalue if LogicalSensorAccuracy::adjust_rawvalue property is set
     *
     * @param $value
     */
    public function setRawvalueAttribute($value)
    {
        $adjustment = $this->property('LogicalSensorAccuracy', 'adjust_rawvalue', true);
        if (!is_null($adjustment)) {
            $value = $value + (int)$adjustment;
        }
        $this->attributes['rawvalue'] = $value;
    }

    /**
     * Check if there is an active threshold
     * from today before now
     *
     * Otherwise check for thresholds
     * in the future (which would be last day's
     * still active threshold)
     *
     * @return mixed
     */
    public function current_threshold()
    {
        $today = $this->thresholds()
                    ->where('starts_at', '<', Carbon::now())
                    ->where('active', true)
                    ->orderBy('starts_at', 'desc')
                    ->first();

        if (!is_null($today))
            return $today;

        $yesterday = $this->thresholds()
            ->where('active', true)
            ->orderBy('starts_at', 'desc')
            ->first();

        return $yesterday;
    }

    /**
     * If soft_state_duration_minutes is null we will fallback to
     * environment variable DEFAULT_SOFT_STATE_DURATION_MINUTES
     *
     * @param $value
     * @return mixed
     */
    public function getSoftStateDurationMinutesAttribute($value)
    {
        if (is_null($value)) {
            return env('DEFAULT_SOFT_STATE_DURATION_MINUTES', 10);
        }

        return $value;
    }

    /**
     * Returns all logical sensor types
     * from db
     *
     * @return array
     */
    public static function types()
    {
        return array_column(LogicalSensor::groupBy('type')->get()->toArray(), 'type');
    }

    /**
     * @return bool
     */
    public function stateOk()
    {
        $t = $this->current_threshold();
        if (is_null($t)) {
            return true;
        }

        if (!is_null($t->rawvalue_lowerlimit)) {
            if ($this->rawvalue < $t->rawvalue_lowerlimit) {
                return false;
            }
        }

        if (!is_null($t->rawvalue_upperlimit)) {
            if ($this->rawvalue > $t->rawvalue_upperlimit) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return float|int|mixed
     */
    public function getCurrentCookedValue()
    {
        if (is_null($this->rawvalue)) {
            return null;
        }

        switch ($this->type) {
            case 'temperature_celsius':
                return round($this->rawvalue, 1);
            case 'humidity_percent':
                return round($this->rawvalue, 1);
            default:
                return $this->rawvalue;
        }
    }

    /**
     * @param $value
     * @return bool
     */
    public function checkRawValue($value)
    {
        return ($value >= $this->rawvalue_lowerlimit && $value <= $this->rawvalue_upperlimit);
    }

    /**
     * @return bool
     */
    public function isCurrentValueLowerThanThreshold()
    {
        $t = $this->current_threshold();
        if (is_null($t)) {
            return false;
        }

        if (!is_null($t->rawvalue_lowerlimit)) {
            if ($this->rawvalue < $t->rawvalue_lowerlimit) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isCurrentValueGreaterThanThreshold()
    {
        $t = $this->current_threshold();
        if (is_null($t)) {
            return false;
        }

        if (!is_null($t->rawvalue_upperlimit)) {
            if ($this->rawvalue > $t->rawvalue_upperlimit) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return mixed|string
     */
    public function typeReadable()
    {
        switch ($this->type) {
            case 'humidity_percent':
                return 'Humidity (%)';
            case 'temperature_celsius':
                return 'Temperature (°C)';
            default:
                return $this->type;
        }
    }

    /**
     * @return mixed|string
     */
    public function valueReadable()
    {
        switch ($this->type) {
            case 'humidity_percent':
                return $this->getCurrentCookedValue() . '%';
            case 'temperature_celsius':
                return $this->getCurrentCookedValue() . '°C';
            default:
                return $this->type;
        }
    }

    /**
     *
     * @return bool
     */
    public function check_notifications_enabled()
    {
        if (is_null($this->physical_sensor))
            return false;

        return $this->physical_sensor->check_notifications_enabled();
    }

    /**
     * @return string
     */
    public function icon()
    {
        return 'memory';
    }

    /**
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function url()
    {
        return url('logical_sensors/' . $this->id);
    }
}
