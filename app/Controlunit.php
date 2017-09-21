<?php

namespace App;

use App\Events\ControlunitDeleted;
use App\Events\ControlunitUpdated;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Controlunit
 *
 * @package App
 * @property string $id
 * @property string $name
 * @property \Carbon\Carbon $heartbeat_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $software_version
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\CriticalState[] $critical_states
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\GenericComponent[] $generic_components
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PhysicalSensor[] $physical_sensors
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Pump[] $pumps
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Valve[] $valves
 * @method static \Illuminate\Database\Query\Builder|\App\Controlunit whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Controlunit whereHeartbeatAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Controlunit whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Controlunit whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Controlunit whereSoftwareVersion($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Controlunit whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Controlunit extends CiliatusModel
{
    use Traits\Uuids, Traits\HasCriticalStates;

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
     *
     */
    public function delete()
    {
        broadcast(new ControlunitDeleted($this->id));

        foreach ($this->physical_sensors as $ps) {
            $ps->controlunit_id = null;
            $ps->save();
        }

        foreach ($this->pumps as $p) {
            $p->controlunit_id = null;
            $p->save();
        }

        foreach ($this->valves as $v) {
            $v->controlunit_id = null;
            $v->save();
        }

        foreach ($this->generic_components as $gc) {
            $gc->controlunit_id = null;
            $gc->save();
        }

        foreach ($this->critical_states as $cs) {
            $cs->setBelongsTo();
        }

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
            broadcast(new ControlunitUpdated($this));
        }

        return $result;
    }

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
}
