<?php

namespace App;

use App\Events\ControlunitDeleted;
use App\Events\ControlunitUpdated;
use App\Traits\HasCriticalStates;
use App\Traits\Uuids;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;

/**
 * Class Controlunit
 * @package App
 */
class Controlunit extends Component
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
    protected $dates = ['created_at', 'updated_at', 'heartbeat_at'];

    /**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Overrides Component->notification_type_name
     *
     * @var string
     */
    protected $notification_type_name = 'controlunits';

    /**
     * @var array
     */
    protected $dispatchesEvents = [
        'updated' => ControlunitUpdated::class,
        'deleting' => ControlunitDeleted::class
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function properties()
    {
        return $this->hasMany('App\Property', 'belongsTo_id')->where('belongsTo_type', 'Controlunit');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function physical_sensors()
    {
        return $this->hasMany('App\PhysicalSensor');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function valves()
    {
        return $this->hasMany('App\Valve');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pumps()
    {
        return $this->hasMany('App\Pump');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function generic_components()
    {
        return $this->hasMany('App\GenericComponent')->with('type');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function critical_states()
    {
        return $this->hasMany('App\CriticalState', 'belongsTo_id')->where('belongsTo_type', 'Controlunit');
    }

    /**
     * @return string
     */
    public function heartbeatOk()
    {
        return Carbon::now()->diffInMinutes($this->heartbeat_at) < env('CONTROLUNIT_MAX_TIMESPAN_MINUTES', 10) && !is_null($this->heartbeat_at);
    }

    /**
     * @return bool
     */
    public function check_notifications_enabled()
    {
        // TODO add attribute
        return true;
    }

    /**
     * @return string
     */
    public function stateOk()
    {
        return $this->heartbeatOk();
    }

    /**
     *
     */
    public function heartbeat()
    {
        $this->heartbeat_at = Carbon::now();
        $this->save();
    }

    /**
     * @return string
     */
    public function generateConfig()
    {
        $config = "[main]\n";
        $config.= "id = {$this->id}\n";
        $config.= "name = {$this->name}\n\n";

        $config.= "[i2c]\n";
        $config.= "bus = {$this->property('ControlunitConnectivity', 'i2c_bus_num', true)}\n\n";

        $config.= "[api]\n";
        $config.= "uri = " . env('APP_URL', 'PLEASE_DEFINE_APP_URL') . "/api/v1\n";
        $config.= "submit_sensorreadings_interval = 180\n";
        $config.= "fetch_desired_states_interval = 10\n";
        $config.= "auth_type = oauth2_personal_token\n\n";

        $config.= "[api_auth_basic]\n";
        $config.= "user = \n";
        $config.= "password = \n\n";

        $config.= "[api_auth_oauth2_personal_token]\n";
        $config.= "token = ";

        foreach ($this->physical_sensors as $ps) {
            $config .= "\n\n" . $ps->generateConfig();
        }

        foreach ($this->valves as $v) {
            $config .= "\n\n" . $v->generateConfig();
        }

        foreach ($this->pumps as $p) {
            $config .= "\n\n" . $p->generateConfig();
        }

        foreach ($this->generic_components as $gc) {
            $config .= "\n\n" . $gc->generateConfig();
        }

        return $config;
    }

    /**
     * @return array
     */
    public function fetchAndAckDesiredStates()
    {
        $desired_states = [
            'Valve' => [],
            'Pump' => []
        ];

        ActionSequenceSchedule::createAndUpdateRunningActions($this);
        ActionSequenceTrigger::createAndUpdateRunningActions($this);
        ActionSequenceIntention::createAndUpdateRunningActions($this);

        foreach (RunningAction::whereNull('finished_at')->get() as $ra) {
            if ($ra->action->target_object()->controlunit_id == $this->id) {
                $desired_states[$ra->action->target_type][$ra->action->target_id] = 'running';
            }
        }

        return $desired_states;
    }

    /**
     * @return string
     */
    public function icon()
    {
        return 'developer_board';
    }

    /**
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function url()
    {
        return url('controlunits/' . $this->id);
    }

    /**
     * @param $type
     * @param $locale
     * @return array|\Illuminate\Contracts\Translation\Translator|null|string
     */
    protected function getCriticalStateNotificationsText($type, $locale)
    {
        return trans('messages.' . $type . '_' . $this->notification_type_name, [
            'controlunit' => $this->name
        ], $locale);
    }
}
