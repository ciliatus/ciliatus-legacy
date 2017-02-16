<?php

namespace App;

use App\Events\ControlunitDeleted;
use App\Events\ControlunitUpdated;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Controlunit
 * @package App
 */
class Controlunit extends CiliatusModel
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
    protected $dates = ['created_at', 'updated_at', 'heartbeat_at'];

    /**
     *
     */
    public function delete()
    {
        broadcast(new ControlunitDeleted($this->id));

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
    public function physical_sensors()
    {
        return $this->hasMany('App\PhysicalSensor')->with('logical_sensors');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function valves()
    {
        return $this->hasMany('App\Valve')->with('pump');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pumps()
    {
        return $this->hasMany('App\Pump')->with('valves');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function generic_components()
    {
        return $this->hasMany('App\GenericComponent')->with('type');
    }

    /**
     * @return string
     */
    public function heartbeatOk()
    {
        return Carbon::now()->diffInMinutes($this->heartbeat_at) < 10 && !is_null($this->heartbeat_at);
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
        $config = "[main]\nid = {$this->id}\nname = {$this->name}\n\n[api]\nuri = " . env('APP_URL') . "/api/v1\ncheck_interval = 180\nauth_type = basic\n\n[api_auth_basic]\nuser = \npassword = ";

        foreach ($this->physical_sensors as $ps) {
            $config .= "\n\n" . $ps->generateConfig();
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
