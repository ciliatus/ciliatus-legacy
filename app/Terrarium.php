<?php

namespace App;

use App\Events\TerrariumUpdated;
use App\Events\TerrariumDeleted;
use App\Http\Transformers\TerrariumTransformer;
use App\Repositories\SensorreadingRepository;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class Terrarium
 *
 * @property mixed physical_sensors
 * @property mixed logical_sensors
 * @property mixed properties
 * @package App
 * @property string $id
 * @property string $name
 * @property string $display_name
 * @property bool $notifications_enabled
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property bool $humidity_critical
 * @property bool $temperature_critical
 * @property bool $heartbeat_critical
 * @property float $cooked_humidity_percent
 * @property float $cooked_temperature_celsius
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ActionSequence[] $action_sequences
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Animal[] $animals
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\GenericComponent[] $generic_components
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\LogicalSensor[] $logical_sensors
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PhysicalSensor[] $physical_sensors
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Valve[] $valves
 * @method static \Illuminate\Database\Query\Builder|\App\Terrarium whereCookedHumidityPercent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Terrarium whereCookedTemperatureCelsius($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Terrarium whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Terrarium whereDisplayName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Terrarium whereHeartbeatCritical($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Terrarium whereHumidityCritical($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Terrarium whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Terrarium whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Terrarium whereNotificationsEnabled($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Terrarium whereTemperatureCritical($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Terrarium whereUpdatedAt($value)
 * @mixin \Eloquent
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
        'notifications_enabled' =>  'boolean',
        'temperature_critical'  =>  'boolean',
        'humidity_critical'     =>  'boolean',
        'heartbeat_critical'    =>  'boolean'
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'display_name'
    ];

    /**
     * @param array $options
     * @return bool
     */
    public function save(array $options = [])
    {

        $this->updateStaticFields();
        $result = parent::save($options);

        if (!isset($options['silent'])) {
            broadcast(new TerrariumUpdated($this));
        }

        return $result;
    }

    /**
     *
     */
    public function delete()
    {
        broadcast(new TerrariumDeleted($this->id));

        parent::delete();
    }

    /**
     *
     */
    public function updateStaticFields()
    {
        $this->temperature_critical = !$this->temperatureOk();
        $this->humidity_critical = !$this->humidityOk();
        $this->heartbeat_critical = !$this->heartbeatOk();
        $this->cooked_humidity_percent = $this->getCurrentHumidity();
        $this->cooked_temperature_celsius = $this->getCurrentTemperature();
    }

    /**
     * @return mixed
     */
    public function properties()
    {
        return $this->hasMany('App\Property', 'belongsTo_id')->where('belongsTo_type', 'Terrarium');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function physical_sensors()
    {
        return $this->hasMany('App\PhysicalSensor', 'belongsTo_id')->with('logical_sensors', 'controlunit')->where('belongsTo_type', 'terrarium');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logical_sensors()
    {
        return $this->hasManyThrough('App\LogicalSensor', 'App\PhysicalSensor', 'belongsTo_id')->where('belongsTo_type', 'terrarium');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function valves()
    {
        return $this->hasMany('App\Valve')->with('pump', 'controlunit');
    }

    /**
     * @return mixed
     */
    public function generic_components()
    {
        return $this->hasMany('App\GenericComponent', 'belongsTo_id')->with('controlunit')->where('belongsTo_type', 'Terrarium');
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function action_sequences()
    {
        return $this->hasMany('App\ActionSequence')->with('actions', 'schedules', 'triggers', 'intentions');
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
     * Returns sensorreadings of a specific type
     * while using caching to deliver optimal performance.
     * Caching will only be used if $history_to is null,
     * meaning I want sensorreadings up to now. Custom
     * timespans will not be cached.
     *
     * @param $type
     * @param $kill_cache = false
     * @param Carbon $history_to
     * @param int $history_minutes
     * @param boolean $ignore_anomalies
     * @return array
     */
    public function getSensorreadingsByType($type,
                                            $kill_cache = false,
                                            Carbon $history_to = null,
                                            $history_minutes = null,
                                            $ignore_anomalies = false)
    {

        // Evaluate if query can be cached
        $cachable = false;
        if (is_null($history_to)) {
            $history_to = Carbon::now();
            $cachable = true;
        }

        // fill history
        if (is_null($history_minutes)) {
            $history_minutes = env('TERRARIUM_DEFAULT_HISTORY_MINUTES', 180);
        }

        // Read, decode and return cache if possible
        $cache_key = 'sensorreadingsByType_' . $this->id . '_' . $type . '_' . $history_minutes;
        if ($cachable) {
            if (Cache::has($cache_key) && !$kill_cache) {
                $cache = Cache::get($cache_key);
                $final_data = json_decode($cache);
                return $final_data;
            }
        }

        // No cache -> get from db
        $history = $this->fetchSensorreadings(
                $type,
                $history_minutes,
                $history_to,
                null,
                null,
                false,
                $ignore_anomalies
        );

        $final_data = array_column($history->toArray(), 'avg_rawvalue');

        // Encode data and put in cache
        if ($cachable) {
            $encoded_data = json_encode($final_data);
            $duration = env('TERRARIUM_DEFAULT_HISTORY_CACHE_MINUTES', 5);
            Cache::put($cache_key, $encoded_data, $duration);
        }

        return $final_data;
    }


    /**
     * @param $type
     * @param int $days
     * @param Carbon $time_to
     * @param Carbon $time_of_day_from
     * @param Carbon $time_of_day_to
     * @return Collection
     */
    public function getSensorreadingStats($type, $days, $time_to, Carbon $time_of_day_from, $time_of_day_to)
    {
        return $this->fetchSensorreadings(
                $type,
                $days*24*60,
                $time_to,
                $time_of_day_from,
                $time_of_day_to,
                true,
                true
        );
    }

    /**
     * @param int $days
     * @param Carbon|null $time_of_day_from
     * @param Carbon|null $time_of_day_to
     * @return Collection
     */
    public function getHumidityStats($days, Carbon $time_of_day_from = null, Carbon $time_of_day_to = null)
    {
        $time_to = Carbon::today();
        return $this->getSensorreadingStats(
                'humidity_percent',
                $days,
                $time_to,
                $time_of_day_from,
                $time_of_day_to
        );
    }


    /**
     * @param int $days
     * @param Carbon $time_of_day_from
     * @param Carbon $time_of_day_to
     * @return Collection
     */
    public function getTemperatureStats($days, Carbon $time_of_day_from = null, Carbon $time_of_day_to = null)
    {
        $time_to = Carbon::today();
        return $this->getSensorreadingStats(
                'temperature_celsius',
                $days,
                $time_to,
                $time_of_day_from,
                $time_of_day_to
        );
    }

    /**
     * @param $type
     * @param null $minutes
     * @param Carbon $time_to
     * @param Carbon|null $time_of_day_from
     * @param Carbon|null $time_of_day_to
     * @param bool $return_stats If true, a float with the average value will be returned
     * @param boolean $ignore_anomalies = false
     * @return Collection
     */
    private function fetchSensorreadings($type,
                                         $minutes,
                                         Carbon $time_to,
                                         Carbon $time_of_day_from = null,
                                         Carbon $time_of_day_to = null,
                                         $return_stats = false,
                                         $ignore_anomalies = false)
    {
        if (is_null($minutes)) {
            $minutes = env('TERRARIUM_DEFAULT_HISTORY_MINUTES', 120);
        }

        if (!is_array($type)) {
            $type = [$type];
        }

        $time_from = clone $time_to;
        $time_from->subMinute($minutes);

        /*
         * Fetch all logical sensors
         * of this terrarium with matching type
         */
        $logical_sensor_ids = [];
        foreach ($this->physical_sensors as $ps) {
            foreach ($ps->logical_sensors()->whereIn('type', $type)->get() as $ls) {
                $logical_sensor_ids[] = $ls->id;
            }
        }

        $query = DB::table('sensorreadings')->where('created_at', '>=', $time_from)
                                            ->where('created_at', '<=', $time_to);

        if ($ignore_anomalies) {
            $query = $query->where('is_anomaly', false);
        }

        if ($return_stats) {
            return (new SensorreadingRepository())->getAvgByLogicalSensor($query, $logical_sensor_ids, $time_of_day_from, $time_of_day_to, true)->get()->first();
        }

        return (new SensorreadingRepository())->getAvgByLogicalSensor($query, $logical_sensor_ids)->get();
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
     * @return bool
     */
    public function check_notifications_enabled()
    {
        return $this->notifications_enabled;
    }

    /**
     *
     */
    public static function rebuild_cache()
    {
        $reading_types = LogicalSensor::types();
        foreach (Terrarium::get() as $t) {
            foreach ($reading_types as $type) {
                echo "Rebuilding $type of {$t->display_name}" . PHP_EOL;
                $t->getSensorreadingsByType($type, true);
            }
        }
    }

    /**
     * @return mixed
     */
    public function background_image_path()
    {
        $files = $this->files()->with('properties')->get();
        foreach ($files as $f) {
            if ($f->property('generic', 'is_default_background') == true) {
                if (!is_null($f->thumb())) {
                    return $f->thumb()->path_external();
                }
                else {
                    return $f->path_external();
                }
            }
        }

        foreach ($this->animals as $a) {
            if (!is_null($a->background_image_path())) {
                return $a->background_image_path();
            }
        }

        return null;
    }

    /**
     * @param array $options
     * @return array
     */
    public function getSuggestions($options = null)
    {
        if (is_null($options)) {
            $options = [
                'critical_states'
            ];
        }
        $suggestions = [];

        foreach ($options as $category) {
            switch ($category) {
                case 'critical_states':
                    $suggestions['critical_states'] = $this->getSuggestionsForCriticalStates();
                    break;
            }
        }

        return $suggestions;
    }

    /**
     * @return array
     * @throws \Exception
     */
    protected function getSuggestionsForCriticalStates()
    {
        $suggestions = [];
        foreach (['temperature_celsius', 'humidity_percent'] as $type) {
            if (!$this->getSuggestionsEnabled($type)) {
                continue;
            }

            $critical_states = CriticalState::where('belongsTo_type', 'LogicalSensor')
                                            ->whereIn('belongsTo_id', array_column(
                                                $this->logical_sensors()->where('type', $type)->get()->toArray(),
                                                'id'
                                            ))
                                            ->where('is_soft_state', false)
                                            ->where('created_at', '>=', $this->getSuggestionTimeframe($type, true))
                                            ->get();

            $first = CriticalState::getFirstTimeUnitViolatingThreshold(
                $critical_states,
                $this->getSuggestionThreshold($type)
            );

            if (!is_null($first)) {
                $suggestions[$type] = $first;
            }
        }

        return $suggestions;
    }

    /**
     * @param $type
     * @param $to_carbon
     * @return int|Carbon|null
     */
    public function getSuggestionTimeframe($type, $to_carbon = false)
    {
        $timeframe = $this->properties()->where('type', 'SuggestionsCriticalStateTimeframe')
                                        ->where('name', $type)
                                        ->get()->first();

        if (!is_null($timeframe)) {
            if ($to_carbon) {
                $now = Carbon::now();
                $method = 'sub' . ucfirst($this->getSuggestionTimeframeUnit($type));
                return $now->$method($timeframe->value);
            }
            return $timeframe->value;
        }

        return null;
    }

    /**
     * @param $type
     * @return string
     */
    public function getSuggestionTimeframeUnit($type)
    {
        $by = $this->properties()->where('type', 'SuggestionsCriticalStateTimeframeUnit')
                                 ->where('name', $type)
                                 ->get()->first();

        if (!is_null($by)) {
            return $by->value;
        }

        return 'month';
    }

    /**
     * @param $type
     * @return null
     */
    public function getSuggestionThreshold($type)
    {
        $threshold = $this->properties()->where('type', 'SuggestionsCriticalStateAmountPerHourThreshold')
                                        ->where('name', $type)
                                        ->get()->first();

        if (!is_null($threshold)) {
            return $threshold->value;
        }
        return null;
    }

    /**
     * @param $type
     * @return bool
     */
    public function getSuggestionsEnabled($type)
    {
        $property = $this->properties()->where('type', 'SuggestionsCriticalStateEnabled')
                                        ->where('name', $type)
                                        ->get()->first();

        if (!is_null($property)) {
            return $property->value == 'On';
        }
        return false;
    }

    /**
     * @param String $type
     * @param bool $value
     */
    public function toggleSuggestions($type, $value)
    {
        $property = $this->properties()->where('type', 'SuggestionsCriticalStateEnabled')
                                       ->where('name', $type)
                                       ->get()->first();

        if (!is_null($property)) {
            $property->value = $value ? 'On' : 'Off';
            $property->save();
            return;
        }

        Property::create([
            'belongsTo_type' => 'Terrarium',
            'belongsTo_id' => $this->id,
            'type' => 'SuggestionsCriticalStateEnabled',
            'name' => $type,
            'value' => $value ? 'On' : 'Off'
        ]);
    }

    /**
     * @param $type
     * @param $timeframe_option
     * @param $timeframe_unit_option
     * @param $threshold_option
     */
    public function setSuggestionSettings($type, $timeframe_option, $timeframe_unit_option, $threshold_option)
    {
        $by = $this->properties()->where('type', 'SuggestionsCriticalStateTimeframeUnit')
                                 ->where('name', $type)
                                 ->get()->first();

        if (!is_null($by)) {
            $by->value = $timeframe_unit_option;
            $by->save();
        }
        else {
            Property::create([
                'belongsTo_type' => 'Terrarium',
                'belongsTo_id' => $this->id,
                'type' => 'SuggestionsCriticalStateTimeframeUnit',
                'name' => $type,
                'value' => $timeframe_unit_option
            ]);
        }

        $threshold = $this->properties()->where('type', 'SuggestionsCriticalStateAmountPerHourThreshold')
                                        ->where('name', $type)
                                        ->get()->first();

        if (!is_null($threshold)) {
            $threshold->value = $threshold_option;
            $threshold->save();
        }
        else {
            Property::create([
                'belongsTo_type' => 'Terrarium',
                'belongsTo_id' => $this->id,
                'type' => 'SuggestionsCriticalStateAmountPerHourThreshold',
                'name' => $type,
                'value' => $threshold_option
            ]);
        }

        $timeframe = $this->properties()->where('type', 'SuggestionsCriticalStateTimeframe')
            ->where('name', $type)
            ->get()->first();

        if (!is_null($timeframe)) {
            $timeframe->value = $timeframe_option;
            $timeframe->save();
        }
        else {
            Property::create([
                'belongsTo_type' => 'Terrarium',
                'belongsTo_id' => $this->id,
                'type' => 'SuggestionsCriticalStateTimeframe',
                'name' => $type,
                'value' => $timeframe_option
            ]);
        }
    }

    /**
     *
     */
    public function generateDefaultSuggestionSettings()
    {
        foreach (['humidity_percent', 'temperature_celsius'] as $type) {
            $this->setSuggestionSettings($type, 1, 'month', 10);
        }
    }

    /**
     * @return array
     */
    public function capabilities()
    {
        $capabilities = [];
        $capabilities[ActionSequence::TEMPLATE_IRRIGATION] =
            $this->hasComponentsForActionSequenceTemplate(ActionSequence::TEMPLATE_IRRIGATION);
        $capabilities[ActionSequence::TEMPLATE_VENTILATE] =
            $this->hasComponentsForActionSequenceTemplate(ActionSequence::TEMPLATE_VENTILATE);
        $capabilities[ActionSequence::TEMPLATE_HEAT_UP] =
            $this->hasComponentsForActionSequenceTemplate(ActionSequence::TEMPLATE_HEAT_UP);
        $capabilities[ActionSequence::TEMPLATE_COOL_DOWN] =
            $this->hasComponentsForActionSequenceTemplate(ActionSequence::TEMPLATE_COOL_DOWN);

        return $capabilities;
    }

    /**
     * Returns true if the terrarium has components
     * which can serve the defined template.
     *
     * @param $template
     * @return bool
     */
    public function hasComponentsForActionSequenceTemplate($template)
    {
        switch ($template) {

            case ActionSequence::TEMPLATE_IRRIGATION:

                return $this->valves->count() > 0;

            case ActionSequence::TEMPLATE_VENTILATE:

                $generic_components = GenericComponentType::getGenericComponentsByIntention(
                    ActionSequenceIntention::TYPE_HUMIDITY_PERCENT,
                    ActionSequenceIntention::INTENTION_DECREASE,
                    $this->generic_components()->getQuery()
                );

                return $generic_components->count() > 0;

            case ActionSequence::TEMPLATE_HEAT_UP:

                $generic_components = GenericComponentType::getGenericComponentsByIntention(
                    ActionSequenceIntention::TYPE_TEMPERATURE_CELSIUS,
                    ActionSequenceIntention::INTENTION_INCREASE,
                    $this->generic_components()->getQuery()
                );

                return $generic_components->count() > 0;

            case ActionSequence::TEMPLATE_COOL_DOWN:

                $generic_components = GenericComponentType::getGenericComponentsByIntention(
                    ActionSequenceIntention::TYPE_TEMPERATURE_CELSIUS,
                    ActionSequenceIntention::INTENTION_DECREASE,
                    $this->generic_components()->getQuery()
                );

                return $generic_components->count() > 0;
        }

        return false;
    }

    /**
     * Generate an Action Sequence for the desired template for this terrarium.
     * Returns Action Sequence on success and
     * false if no compatible components could be found.
     *
     * @param $template
     * @param $duration_minutes
     * @param bool $runonce
     * @return bool|ActionSequence
     */
    public function generateActionSequenceByTemplate($template, $duration_minutes, $runonce = false)
    {
        if (!$this->hasComponentsForActionSequenceTemplate($template)) {
            return false;
        }

        $action_sequence = ActionSequence::create([
            'name' => trans('labels.' . $template) . ' ' . $this->display_name . ' ' . $duration_minutes . 'm',
            'terrarium_id' => $this->id,
            'duration_minutes' => $duration_minutes
        ]);

        $action_sequence->runonce = $runonce;
        $action_sequence->duration_minutes = $duration_minutes;
        $action_sequence->save();

        if ($action_sequence->generateActionsByTemplate($template)) {
            return $action_sequence;
        }
        else {
            $action_sequence->delete();
            return false;
        }
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
