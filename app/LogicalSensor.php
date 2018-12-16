<?php

namespace App;

use App\Events\LogicalSensorDeleted;
use App\Events\LogicalSensorUpdated;
use App\Traits\HasCriticalStates;
use App\Traits\Uuids;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;

/**
 * Class LogicalSensor
 * @package App
 */
class LogicalSensor extends Component
{
    use Uuids, HasCriticalStates, Notifiable;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */

    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['name', 'physical_sensor_id', 'type'];

    /**
     * Overrides Component->notification_type_name
     *
     * @var string
     */
    protected $notification_type_name = 'logical_sensors';

    /**
     * @var array
     */
    protected $dispatchesEvents = [
        'updated' => LogicalSensorUpdated::class,
        'deleting' => LogicalSensorDeleted::class
    ];

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
        return $this->belongsTo('App\PhysicalSensor');
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
     * @return LogicalSensor
     */
    public function belongsTo_object()
    {
        return $this->physical_sensor;
    }

    /**
     * @return bool|int|mixed
     */
    public function incrementAnomaliesDetected()
    {
        $counter = $this->property('AnomalyDetection', 'counter');

        if (is_null($counter)) {
            $counter = $this->setProperty('AnomalyDetection', 'counter', 0);
        }

        $counter->value = (int)$counter->value + 1;
        $counter->save();

        return $counter->value;
    }

    /**
     * @return int
     */
    public function getAnomalyCount()
    {
        return (int)$this->property('AnomalyDetection', 'counter', true);
    }

    /**
     * @return float|int
     */
    public function getRawvalueAdjustment()
    {
        $adjustment = $this->property('LogicalSensorAccuracy', 'adjust_rawvalue', true);
        return is_null($adjustment) ? 0 : (float)$adjustment;
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
        $reading_types = array_column(LogicalSensor::groupBy('type')->get()->toArray(), 'type');
        return array_unique(
            array_merge(
                $reading_types,
                ['humidity_percent', 'temperature_celsius']
            ),
            SORT_STRING
        );
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

        if (!is_null($t->adjusted_value_lowerlimit)) {
            if ($this->adjusted_value < $t->adjusted_value_lowerlimit) {
                return false;
            }
        }

        if (!is_null($t->adjusted_value_upperlimit)) {
            if ($this->adjusted_value > $t->adjusted_value_upperlimit) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns a reason why stateOk() is false
     * @return array
     */
    public function getStateDetails()
    {
        if ($this->stateOk()) {
            return ['STATE_OK'];
        }

        $t = $this->current_threshold();
        if (is_null($t)) {
            return ['STATE_OK'];
        }

        $state_details = [];
        if (!is_null($t->adjusted_value_lowerlimit)) {
            if ($this->adjusted_value < $t->adjusted_value_lowerlimit) {
                $state_details[] = 'LOWERLIMIT_DECEEDED';
            }
        }

        if (!is_null($t->adjusted_value_upperlimit)) {
            if ($this->adjusted_value > $t->adjusted_value_upperlimit) {
                $state_details[] = 'UPPERLIMIT_EXCEEDED';
            }
        }

        return $state_details;
    }

    /**
     * @return float|int|mixed
     */
    public function getCurrentCookedValue()
    {
        if (is_null($this->adjusted_value)) {
            return null;
        }

        switch ($this->type) {
            case 'temperature_celsius':
                return round($this->adjusted_value, 1);
            case 'humidity_percent':
                return round($this->adjusted_value, 1);
            default:
                return $this->adjusted_value;
        }
    }

    /**
     * @param $value
     * @return bool
     */
    public function checkAdjustedValue($value)
    {
        return ($value >= $this->adjusted_value_lowerlimit && $value <= $this->adjusted_value_upperlimit);
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

        if (!is_null($t->adjusted_value_lowerlimit)) {
            if ($this->adjusted_value < $t->adjusted_value_lowerlimit) {
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

        if (!is_null($t->adjusted_value_upperlimit)) {
            if ($this->adjusted_value > $t->adjusted_value_upperlimit) {
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
        return 'puls';
    }

    /**
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function url()
    {
        return url('logical_sensors/' . $this->id);
    }

    /**
     * @param $type
     * @param $locale
     * @param string $details
     * @return array|\Illuminate\Contracts\Translation\Translator|null|string
     */
    protected function getCriticalStateNotificationsText($type, $locale, $details = 'UNKNOWN')
    {
        return trans(
            'messages.' . $type . '_' . $this->notification_type_name . '.' . $this->type . '.' . $details,
            [
                'logical_sensor' => $this->name,
                $this->type => $this->getCurrentCookedValue()
            ],
            $locale
        );
    }
}
