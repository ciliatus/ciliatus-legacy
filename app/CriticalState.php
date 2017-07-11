<?php

namespace App;

use App\Events\CriticalStateCreated;
use App\Events\CriticalStateDeleted;
use Carbon\Carbon;
use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Symfony\Component\Debug\Exception\FatalThrowableError;

/**
 * Class CriticalState
 *
 * @package App
 * @property string $id
 * @property string $name
 * @property string $belongsTo_type
 * @property string $belongsTo_id
 * @property bool $is_soft_state
 * @property \Carbon\Carbon $notifications_sent_at
 * @property \Carbon\Carbon $recovered_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @method static \Illuminate\Database\Query\Builder|\App\CriticalState whereBelongsToId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CriticalState whereBelongsToType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CriticalState whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CriticalState whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CriticalState whereIsSoftState($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CriticalState whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CriticalState whereNotificationsSentAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CriticalState whereRecoveredAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CriticalState whereUpdatedAt($value)
 * @mixin \Eloquent
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
        'is_soft_state'
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
        $new = new CriticalState($attributes);
        $new->save();

        Log::create([
            'target_type'   =>  explode('\\', get_class($new))[count(explode('\\', get_class($new)))-1],
            'target_id'     =>  $new->id,
            'associatedWith_type' => $new->belongsTo_type,
            'associatedWith_id' => $new->belongsTo_id,
            'action'        => 'create'
        ]);

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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function properties()
    {
        return $this->hasMany('App\Property', 'belongsTo_id')->where('belongsTo_type', 'CriticalState');
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
                        if ($u->setting('notifications_terraria_enabled') == 'on') {
                            $ls = LogicalSensor::find($this->belongsTo_id);
                            if (is_null($ls)) {
                                \Log::error('CriticalState ' . $this->id . ' belongs to LogicalSensor ' . $this->belongsTo_id . ' which could not be found.');
                                break;
                            }
                            $u->message(trans('messages.critical_state_notification_logical_sensor.' . $ls->type, [
                                'logical_sensor' => $ls->name,
                                $ls->type => $ls->getCurrentCookedValue()
                            ], '', $u->locale));
                        }
                        break;

                    case 'Controlunit':
                        if ($u->setting('notifications_controlunits_enabled') == 'on') {
                            $cu = Controlunit::find($this->belongsTo_id);
                            if (is_null($cu)) {
                                \Log::error('CriticalState ' . $this->id . ' belongs to Controlunit ' . $this->belongsTo_id . ' which could not be found.');
                                break;
                            }
                            $u->message(trans('messages.critical_state_notification_controlunit', [
                                'controlunit' => $cu->name
                            ], '', $u->locale));
                        }
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
                        if ($u->setting('notifications_terraria_enabled') == 'on') {
                            $ls = LogicalSensor::find($this->belongsTo_id);
                            if (is_null($ls)) {
                                \Log::error('CriticalState ' . $this->id . ' recovered belongs to LogicalSensor ' . $this->belongsTo_id . ' which could not be found.');
                                break;
                            }
                            $u->message(trans('messages.critical_state_recovery_notification_logical_sensor.' . $ls->type, [
                                'logical_sensor' => $ls->name,
                                $ls->type => $ls->getCurrentCookedValue()
                            ], '', $u->locale));
                        }
                        break;

                    case 'Controlunit':
                        if ($u->setting('notifications_controlunits_enabled') == 'on') {
                            $cu = Controlunit::find($this->belongsTo_id);
                            if (is_null($cu)) {
                                \Log::error('CriticalState ' . $this->id . ' recovered belongs to Controlunit ' . $this->belongsTo_id . ' which could not be found.');
                                break;
                            }
                            $u->message(trans('messages.critical_state_recovery_notification_controlunit', [
                                'controlunit' => $cu->name
                            ], '', $u->locale));
                        }
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
        if (!$this->is_soft_state) {
            $this->notifyRecovered();
        }

        $this->recovered_at = Carbon::now();
        $this->save();

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
            $class_name = 'App\\' . ucfirst($this->belongsTo_type);
            if (!class_exists($class_name)) {
                \Log::warning(__CLASS__ . ' "' . $this->name . '" (' . $this->id . ') belongs to object of ' .
                    'unknown class "' . $class_name . '" (' . $this->belongsTo_id . '). Maybe belongsTo is empty but ' .
                    'not null?');
                return null;
            }

            $object = $class_name::find($this->belongsTo_id);
            return $object;
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
                $existing_cs = $ls->critical_states()->whereNull('recovered_at')->get();

                if ($existing_cs->count() < 1) {
                    CriticalState::create([
                        'belongsTo_type' => 'LogicalSensor',
                        'belongsTo_id'   => $ls->id,
                        'is_soft_state'  => true
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

        foreach (Controlunit::get() as $cu) {
            if (!$cu->stateOk()) {
                $existing_cs = $cu->critical_states()->whereNull('recovered_at')->get();

                if ($existing_cs->count() < 1) {
                    CriticalState::create([
                        'belongsTo_type' => 'Controlunit',
                        'belongsTo_id'   => $cu->id
                    ]);

                    $result['created']++;
                }
                else {
                    foreach ($existing_cs as $cs) {
                        if ($cs->created_at->diffInMinutes(Carbon::now()) > env('DEFAULT_SOFT_STATE_DURATION_MINUTES', 10)
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
     * Walks over the critical states collection and assigns each
     * hour the amount of active critical states.
     *
     * The returned timeline is sorted by amount of active
     * critical states per hour in descending order.
     *
     * @param Collection $critical_states
     * @return array
     */
    public static function getTimelineFromCriticalStates(Collection $critical_states)
    {
        $timeline = [];
        foreach ($critical_states as $cs) {

            $hour_start = $cs->created_at->hour;
            $hour_end = is_null($cs->recovered_at) ? 24 : $cs->recovered_at->hour;

            for ($hour = $hour_start; $hour <= $hour_end; $hour++) {
                if (!isset($timeline[$hour])) {
                    $timeline[$hour] = 0;
                }
                $timeline[$hour] += 1;
            }

        }

        arsort($timeline);

        return $timeline;
    }

    /**
     * Calls getTimelineFromCriticalStates and returns the the first
     * (sorted by time in descending order) time
     *
     * @param Collection $critical_states
     * @param $threshold
     * @return mixed
     */
    public static function getFirstTimeUnitViolatingThreshold(Collection $critical_states, $threshold)
    {
        $cs_timeline = CriticalState::getTimelineFromCriticalStates($critical_states);

        foreach ($cs_timeline as $k=>$v) {
            if ($v < (int)$threshold) {
                unset($cs_timeline[$k]);
            }
        }

        if (count($cs_timeline) < 1) {
            return null;
        }

        ksort($cs_timeline);
        reset($cs_timeline);
        return [key($cs_timeline) => current($cs_timeline)];
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
