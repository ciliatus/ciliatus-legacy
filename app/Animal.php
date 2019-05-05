<?php

namespace App;

use App\Events\AnimalDeleted;
use App\Events\AnimalUpdated;
use App\Repositories\AnimalFeedingSchedulePropertyRepository;
use App\Repositories\AnimalWeighingSchedulePropertyRepository;
use App\Traits\Uuids;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;

/**
 * Class Animal
 * @package App
 */
class Animal extends CiliatusModel
{
    use Uuids, Notifiable;

    /**
     * @var array
     */
    protected  $dates = ['created_at', 'updated_at', 'birth_date', 'death_date'];

    /**
     * @var array
     */
    protected $fillable = ['display_name', 'common_name', 'lat_name', 'gender'];

    /**
     * @var array
     */
    protected  $casts = [
        'notifications_enabled' =>  'boolean'
    ];

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $dispatchesEvents = [
        'updated' => AnimalUpdated::class,
        'deleting' => AnimalDeleted::class
    ];

    /**
     * @return mixed
     */
    public function terrarium()
    {
        return $this->belongsTo('App\Terrarium');
    }

    /**
     * @return mixed
     */
    public function properties()
    {
        return $this->hasMany('App\Property', 'belongsTo_id')->where('belongsTo_type', 'Animal');
    }

    /**
     * @return mixed
     */
    public function events()
    {
        return $this->hasMany('App\Event', 'belongsTo_id')->where('belongsTo_type', 'Animal')->orderBy('created_at', 'DESC');
    }

    /**
     * @return mixed
     */
    public function feedings()
    {
        return $this->hasMany('App\AnimalFeedingEvent', 'belongsTo_id');
    }

    /**
     * @return mixed
     */
    public function feeding_schedules()
    {
        return $this->hasMany('App\AnimalFeedingScheduleProperty', 'belongsTo_id');
    }

    /**
     * @return array
     */
    public function getDueFeedingSchedules()
    {
        $feeding_schedules = [
            'due' => [],
            'overdue' => []
        ];

        foreach ($this->feeding_schedules as $afs) {
            $afs = (new AnimalFeedingSchedulePropertyRepository($afs))->show();
            if ($afs->next_feeding_at_diff == 0) {
                $feeding_schedules['due'][] = $afs;
            }
            elseif ($afs->next_feeding_at_diff < 0) {
                $feeding_schedules['overdue'][] = $afs;
            }
        }

        return $feeding_schedules;
    }

    /**
     * @return mixed
     */
    public function weighings()
    {
        return $this->hasMany('App\AnimalWeighingEvent', 'belongsTo_id');
    }

    /**
     * @return mixed
     */
    public function weighing_schedules()
    {
        return $this->hasMany('App\AnimalWeighingScheduleProperty', 'belongsTo_id');
    }

    /**
     * @return array
     */
    public function getDueWeighingSchedules()
    {
        $weighing_schedules = [
            'due' => [],
            'overdue' => []
        ];

        foreach ($this->weighing_schedules as $afs) {
            $afs = (new AnimalWeighingSchedulePropertyRepository($afs))->show();
            if ($afs->next_weighing_at_diff == 0) {
                $weighing_schedules['due'][] = $afs;
            }
            elseif ($afs->next_weighing_at_diff < 0) {
                $weighing_schedules['overdue'][] = $afs;
            }
        }

        return $weighing_schedules;
    }

    /**
     * @return string
     */
    public function gender_icon()
    {
        switch ($this->gender) {
            case 'male':
                return 'mars';
                break;
            case 'female':
                return 'venus';
                break;
            default:
                return 'genderless';
        }
    }

    /**
     * @return array
     */
    public function getAge()
    {
        if (is_null($this->death_date)) {
            $compare_at = Carbon::now();
        }
        else {
            $compare_at = $this->death_date;
        }
        if ($compare_at->diffInYears($this->birth_date) >= 2) {
            $amount = $compare_at->diffInYears($this->birth_date);
            return [$amount, 'years'];

        }
        if ($compare_at->diffInMonths($this->birth_date) > 1) {
            $amount = $compare_at->diffInMonths($this->birth_date);
            return [$amount, 'months'];
        }

        $amount = $compare_at->diffInDays($this->birth_date);
        return [$amount, 'days'];
    }

    /**
     * @param null $type
     * @return mixed
     */
    public function last_feeding($type = null)
    {
        if (is_null($type)) {
            return $this->feedings()->orderBy('created_at', 'DESC')->limit(1)->get()->first();
        }
        else {
            return $this->feedings()->orderBy('created_at', 'DESC')->where('name', $type)->limit(1)->get()->first();
        }
    }

    /**
     * @return mixed
     */
    public function last_weighing()
    {
        return $this->weighings()->orderBy('created_at', 'DESC')->limit(1)->get()->first();
    }

    /**
     * @return mixed
     */
    public function biography_entries()
    {
        return $this->hasMany('App\Event', 'belongsTo_id')->where('type', 'BiographyEntry')
                                                          ->where('belongsTo_type', 'Animal')
                                                          ->with('properties');
    }

    /**
     * @return mixed
     */
    public function caresheets()
    {
        return $this->hasMany('App\Event', 'belongsTo_id')
                    ->where('type', 'AnimalCaresheet')
                    ->where('belongsTo_type', 'Animal')
                    ->with('properties');
    }

    /**
     * Create an animal caresheet
     * Settings:
     *  sensor_history_days: How far should ciliatus go back to fetch historic data for the following:
     *  include_terrarium_history
     *  include_biography_entries
     *  include_weight
     *  include_feedings
     *
     * @param array $settings
     * @return CiliatusModel|Event
     */
    public function generate_caresheet($settings = null)
    {
        if (is_null($settings)) {
            $settings = [
                'include_terrarium_history',
                'include_biography_entries',
                'include_weight',
                'include_feedings'
            ];
        }

        if (!isset($settings['sensor_history_days'])) {
            $settings['sensor_history_days'] = env('DEFAULT_CARESHEET_SENSOR_HISTORY_DAYS', 30);
        }

        if (!isset($settings['data_history_days'])) {
            $settings['data_history_days'] = env('DEFAULT_CARESHEET_DATA_HISTORY_DAYS', 180);
        }

        $caresheet = Event::create([
            'belongsTo_type' => 'Animal',
            'belongsTo_id' => $this->id,
            'type' => 'AnimalCaresheet',
            'name' => trans_choice('labels.caresheets', 1) . ' ' . $this->display_name
        ]);

        $caresheet->create_property('AnimalCaresheetProperty', 'sensor_history_days', $settings['sensor_history_days']);
        $caresheet->create_property('AnimalCaresheetProperty', 'data_history_days', $settings['data_history_days']);

        if (!is_null($this->terrarium) && in_array('include_terrarium_history', $settings)) {
            $caresheet->create_property('AnimalCaresheetProperty', 'terrarium_id', $this->terrarium_id);
            $caresheet->create_property('AnimalCaresheetProperty', 'history_from', Carbon::now()->subDays($settings['sensor_history_days']));

            /*
             * Get Humidity Stats
             */
            $all_stats =
                $this->terrarium->getHumidityStats(
                    $settings['sensor_history_days'],
                    Carbon::today()
                );

            if (!is_null($all_stats)) {
                $caresheet->create_property('AnimalCaresheetProperty', 'terrarium_average_humidity',
                    $all_stats->avg_adjusted_value
                );
                $caresheet->create_property('AnimalCaresheetProperty', 'terrarium_min_humidity',
                    $all_stats->min_adjusted_value
                );
                $caresheet->create_property('AnimalCaresheetProperty', 'terrarium_max_humidity',
                    $all_stats->max_adjusted_value
                );
            }

            
            $day_stats = 
                $this->terrarium->getHumidityStats(
                    $settings['sensor_history_days'],
                    Carbon::today(),
                    Carbon::createFromTime(7, 0, 0),
                    Carbon::createFromTime(20, 0, 0)
                );

            if (!is_null($day_stats)) {
                $caresheet->create_property('AnimalCaresheetProperty', 'terrarium_average_humidity_day',
                    $day_stats->avg_adjusted_value
                );
                $caresheet->create_property('AnimalCaresheetProperty', 'terrarium_min_humidity_day',
                    $day_stats->min_adjusted_value
                );
                $caresheet->create_property('AnimalCaresheetProperty', 'terrarium_max_humidity_day',
                    $day_stats->max_adjusted_value
                );
            }


            $night_stats =
                $this->terrarium->getHumidityStats(
                    $settings['sensor_history_days'],
                    Carbon::today(),
                    Carbon::createFromTime(20, 0, 0),
                    Carbon::createFromTime(7, 0, 0)
                );

            if (!is_null($night_stats)) {
                $caresheet->create_property('AnimalCaresheetProperty', 'terrarium_average_humidity_night',
                    $night_stats->avg_adjusted_value
                );
                $caresheet->create_property('AnimalCaresheetProperty', 'terrarium_min_humidity_night',
                    $night_stats->min_adjusted_value
                );
                $caresheet->create_property('AnimalCaresheetProperty', 'terrarium_max_humidity_night',
                    $night_stats->max_adjusted_value
                );
            }

            
            /*
             * Get Temperature stats
             */
            $all_stats =
                $this->terrarium->getTemperatureStats(
                    $settings['sensor_history_days'],
                    Carbon::today()
                );

            if (!is_null($all_stats)) {
                $caresheet->create_property('AnimalCaresheetProperty', 'terrarium_average_temperature',
                    $all_stats->avg_adjusted_value
                );
                $caresheet->create_property('AnimalCaresheetProperty', 'terrarium_min_temperature',
                    $all_stats->min_adjusted_value
                );
                $caresheet->create_property('AnimalCaresheetProperty', 'terrarium_max_temperature',
                    $all_stats->max_adjusted_value
                );
            }


            $day_stats =
                $this->terrarium->getTemperatureStats(
                    $settings['sensor_history_days'],
                    Carbon::today(),
                    Carbon::createFromTime(7, 0, 0),
                    Carbon::createFromTime(20, 0, 0)
                );

            if (!is_null($day_stats)) {
                $caresheet->create_property('AnimalCaresheetProperty', 'terrarium_average_temperature_day',
                    $day_stats->avg_adjusted_value
                );
                $caresheet->create_property('AnimalCaresheetProperty', 'terrarium_min_temperature_day',
                    $day_stats->min_adjusted_value
                );
                $caresheet->create_property('AnimalCaresheetProperty', 'terrarium_max_temperature_day',
                    $day_stats->max_adjusted_value
                );
            }


            $night_stats =
                $this->terrarium->getTemperatureStats(
                    $settings['sensor_history_days'],
                    Carbon::today(),
                    Carbon::createFromTime(20, 0, 0),
                    Carbon::createFromTime(7, 0, 0)
                );

            if (!is_null($night_stats)) {
                $caresheet->create_property('AnimalCaresheetProperty', 'terrarium_average_temperature_night',
                    $night_stats->avg_adjusted_value
                );
                $caresheet->create_property('AnimalCaresheetProperty', 'terrarium_min_temperature_night',
                    $night_stats->min_adjusted_value
                );
                $caresheet->create_property('AnimalCaresheetProperty', 'terrarium_max_temperature_night',
                    $night_stats->max_adjusted_value
                );
            }
        }

        if (in_array('include_biography_entries', $settings)) {
            $caresheet->create_property('AnimalCaresheetProperty', 'biography_entries',
                $this->biography_entries()
                     ->where('created_at', '>=', Carbon::now()->subDays((int)$settings['data_history_days'])->toDateTimeString())
                     ->get()
                     ->implode('id', ',')
            );
        }

        if (in_array('include_weight', $settings)) {
            $caresheet->create_property('AnimalCaresheetProperty', 'weighings',
                $this->weighings()
                     ->where('created_at', '>=', Carbon::now()->subDays((int)$settings['data_history_days'])->toDateTimeString())
                     ->get()
                     ->implode('id', ',')
            );
        }

        if (in_array('include_feedings', $settings)) {
            $caresheet->create_property('AnimalCaresheetProperty', 'feedings',
                $this->feedings()
                     ->where('created_at', '>=', Carbon::now()->subDays((int)$settings['data_history_days'])->toDateTimeString())
                     ->get()
                     ->implode('id', ',')
            );
        }

        return $caresheet;
    }

    /**
     * @param $id
     */
    public function delete_caresheet($id)
    {
        $caresheet = Event::where('type', 'AnimalCaresheet')->find($id);
        if (!is_null($caresheet)) {
            foreach ($caresheet->properties as $prop) {
                $prop->delete();
            }
            $caresheet->delete();
        }
    }

    /**
     * @return mixed
     */
    public function background_image_path()
    {
        $file_id = null;
        $prop = $this->property('generic', 'background_file_id');
        if (is_null($prop)) {
            return null;
        }

        $file_id = $prop->value;

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

        \Log::warning('Using non-thumb as Animal background. Animal: ' . $this->id . ', File: ' . $file->id);
        return $file->path_external();
    }

    /**
     * @return string
     */
    public function icon()
    {
        return 'paw';
    }

    /**
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function url()
    {
        return url('animals/' . $this->id);
    }
}
