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
            if (!is_null($this->belongsTo_object())) {
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
     *
     */
    public function notify()
    {
        if (!$this->deleteIfOrphaned()) {
            return;
        }

        $this->belongsTo_object()->sendNotifications('critical_state_notification');
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
        if (!$this->deleteIfOrphaned()) {
            return;
        }

        $this->belongsTo_object()->sendNotifications('critical_state_recovery_notification');
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
     * @param CiliatusModel $component
     * @return bool
     */
    public function notifyIfNecessary(CiliatusModel $component)
    {
        if ($component->created_at->diffInMinutes(Carbon::now()) > $component->soft_state_duration_minutes
            && is_null($this->notifications_sent_at)) {

            $this->is_soft_state = false;
            $this->save(['silent']);
            $this->notify();

            return true;
        }

        return false;
    }

    /**
     * @return bool|CiliatusModel
     */
    public function deleteIfOrphaned()
    {
        if (is_null($this->belongsTo_type) || is_null($this->belongsTo_id)) {
            $this->delete();
            return false;
        }

        try {
            $cs_belongs = ('App\\' . $this->belongsTo_type)::find($this->belongsTo_id);
        }
        catch (FatalThrowableError $ex) {
            $this->delete();
            return false;
        }

        if (is_null($cs_belongs)) {
            $this->delete();
            return false;
        }

        return $cs_belongs;
    }

    /**
     * Evaluates critical states
     * Creates/deletes
     */
    public static function evaluate()
    {
        LogicalSensor::evaluateCriticalStates();
        Controlunit::evaluateCriticalStates();

        foreach (CriticalState::whereNull('recovered_at')->get() as $cs) {
            $cs_belongs = $cs->deleteIfOrphaned();
            if (!$cs_belongs) {
                continue;
            }

            if ($cs_belongs->stateOk()) {
                $cs->recover();
            }
        }
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
