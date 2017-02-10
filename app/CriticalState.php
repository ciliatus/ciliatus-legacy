<?php

namespace App;

use App\Events\CriticalStateCreated;
use App\Events\CriticalStateDeleted;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Debug\Exception\FatalThrowableError;

/**
 * Class CriticalState
 * @package App
 */
class CriticalState extends CiliatusModel
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
    protected $fillable = [
        'belongsTo_type',
        'belongsTo_id',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'is_soft_state' =>  'boolean'
    ];

    /**
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'recovered_at', 'notifications_sent_at'];

    /**
     * @param array $attributes
     * @return CiliatusModel|CriticalState
     */
    public static function create(array $attributes = [])
    {
        $new = parent::create($attributes);
        $new->save();

        Log::create([
            'target_type'   =>  explode('\\', get_class($new))[count(explode('\\', get_class($new)))-1],
            'target_id'     =>  $new->id,
            'associatedWith_type' => $new->belongsTo_type,
            'associatedWith_id' => $new->belongsTo_id,
            'action'        => 'create'
        ]);

        $new->is_soft_state = true;
        $new->save();

        broadcast(new CriticalStateCreated($new));

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
            'associatedWith_type' => $this->belongsTo_type,
            'associatedWith_id' => $this->belongsTo_id,
            'action'        => 'delete'
        ]);

        broadcast(new CriticalStateDeleted($this->id));

        parent::delete();
    }

    /**
     * @param array $options
     * @return bool
     */
    public function save(array $options = [])
    {
        if (!in_array('no_new_name', $options)) {
            $this->name = 'CS_';
            if (!is_null($this->belongsTo_type) && !is_null($this->belongsTo_id)) {
                $this->name .= $this->belongsTo_object()->name;
            }
            $this->name .= '_' . Carbon::parse($this->created_at)->format('y-m-d_H:i:s');
            $this->save(['silent', 'no_new_name']);
        }

        return parent::save($options);
    }

    /**
     *
     */
    public function notify()
    {
        if (!is_null($this->belongsTo_object())) {
            if ($this->belongsTo_object()->check_notifications_enabled() !== true) {
                return;
            }
        }

        foreach (User::get() as $u) {
            if ($u->setting('notifications_enabled') == 'on') {
                switch ($this->belongsTo_type) {
                    case 'LogicalSensor':
                        $ls = LogicalSensor::find($this->belongsTo_id);
                        if (is_null($ls)) {
                            \Log::error('CriticalState ' . $this->id . ' belongs to LogicalSensor ' . $this->belongsTo_id . ' which could not be found.');
                            break;
                        }
                        $u->message(trans('messages.critical_state_notification_logical_sensor.' . $ls->type, [
                            'logical_sensor' => $ls->name,
                            $ls->type => $ls->getCurrentCookedValue()
                        ]));
                        break;
                    default:
                        $u->message(trans('messages.critical_state_generic', [
                            'critical_state' => $this->name
                        ]));
                }

            }
        }

        $this->notifications_sent_at = Carbon::now();
        $this->save(['silent']);

        Log::create([
            'target_type' => explode('\\', get_class($this))[count(explode('\\', get_class($this))) - 1],
            'target_id' => $this->id,
            'associatedWith_type' => $this->belongsTo_type,
            'associatedWith_id' => $this->belongsTo_id,
            'action' => 'notify'
        ]);

    }

    /**
     *
     */
    public function notifyRecovered()
    {
        if (!is_null($this->belongsTo_object())) {
            if ($this->belongsTo_object()->check_notifications_enabled() !== true) {
                return;
            }
        }

        foreach (User::get() as $u) {
            if ($u->setting('notifications_enabled') == 'on') {
                switch ($this->belongsTo_type) {
                    case 'LogicalSensor':
                        $ls = LogicalSensor::find($this->belongsTo_id);
                        if (is_null($ls)) {
                            \Log::error('CriticalState ' . $this->id . ' recovered belongs to LogicalSensor ' . $this->belongsTo_id . ' which could not be found.');
                            break;
                        }
                        $u->message(trans('messages.critical_state_recovery_notification_logical_sensor.' . $ls->type, [
                            'logical_sensor' => $ls->name,
                            $ls->type => $ls->getCurrentCookedValue()
                        ]));
                        break;
                    default:
                        $u->message(trans('messages.critical_state_generic', [
                            'critical_state' => $this->name
                        ]));
                }
            }
        }

        $this->notifications_sent_at = Carbon::now();
        $this->save(['silent']);

        Log::create([
            'target_type' => explode('\\', get_class($this))[count(explode('\\', get_class($this))) - 1],
            'target_id' => $this->id,
            'associatedWith_type' => $this->belongsTo_type,
            'associatedWith_id' => $this->belongsTo_id,
            'action' => 'notify_recovered'
        ]);

    }

    /**
     *
     */
    public function recover()
    {
        if (!$this->is_soft_state)
            $this->notifyRecovered();

        $this->recovered_at = Carbon::now();
        $this->save(['silent']);

        Log::create([
            'target_type' => explode('\\', get_class($this))[count(explode('\\', get_class($this))) - 1],
            'target_id' => $this->id,
            'associatedWith_type' => $this->belongsTo_type,
            'associatedWith_id' => $this->belongsTo_id,
            'action' => 'recover'
        ]);

    }

    /**
     * @return null|CiliatusModel
     */
    public function belongsTo_object()
    {
        if (!is_null($this->belongsTo_type) && !is_null($this->belongsTo_id)) {
            $obj = ('App\\' . ucfirst($this->belongsTo_type))::find($this->belongsTo_id);
            if (!is_null($obj)) {
                $obj->current_threshold = $obj->current_threshold();
            }
            return $obj;
        }

        return null;
    }

    /**
     * Evaluates critical states
     * Creates/deletes
     */
    public static function evaluate()
    {

        $result = [
            'created' => 0,
            'deleted' => 0,
            'notified'=> 0,
            'recovered'=>0
        ];

        /*
         * Evaluate LogicalSensor states
         * and create CriticalStates for
         * critical sensors
         */
        foreach (LogicalSensor::get() as $ls) {
            if (!$ls->stateOk()) {
                $existing_cs = CriticalState::where('belongsTo_type', 'LogicalSensor')
                    ->where('belongsTo_id', $ls->id)
                    ->whereNull('recovered_at')
                    ->get();
                if ($existing_cs->count() < 1) {
                    CriticalState::create([
                        'belongsTo_type' => 'LogicalSensor',
                        'belongsTo_id'   => $ls->id
                    ]);

                    $result['created']++;
                }
                else {
                    foreach ($existing_cs as $cs) {
                        if ($cs->created_at->diffInMinutes(Carbon::now()) > $ls->soft_state_duration_minutes
                            && is_null($cs->notifications_sent_at)) {

                            $cs->is_soft_state = false;
                            $cs->save(['silent']);
                            $cs->notify();

                            $result['notified']++;
                        }
                    }
                }
            }
        }

        /*
         * Evaluate active CriticalStates
         * and recover them in case they are stateOk
         *
         * Delete them in case their belonging
         * doest not exist
         */
        foreach (CriticalState::whereNull('recovered_at')->get() as $cs) {
            if (!is_null($cs->belongsTo_type) && !is_null($cs->belongsTo_id)) {
                $cs_belongs = nulL;
                try {
                    $cs_belongs = ('App\\' . $cs->belongsTo_type)::find($cs->belongsTo_id);
                }
                catch (FatalThrowableError $ex) {
                    $cs->delete();
                    $result['deleted']++;
                }

                if (is_null($cs_belongs)) {
                    $cs->delete();
                    $result['deleted']++;
                }

                if ($cs_belongs->stateOk()) {
                    $cs->recover();
                    $result['recovered']++;
                }
            }
        }

        return $result;
    }

    /**
     *
     */
    public function icon()
    {
        if ($this->is_soft_state) {
            return 'error_outline';
        }

        return 'error';
    }

    /**
     *
     */
    public function url()
    {
        return url('critical_states/' . $this->id);
    }
}
