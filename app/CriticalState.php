<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

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
    protected $dates = ['created_at', 'updated_at', 'recovered_at', 'notifications_sent_at'];

    /**
     * @param array $attributes
     * @return CiliatusModel|CriticalState
     */
    public static function create(array $attributes = [])
    {
        $new = parent::create($attributes);
        Log::create([
            'target_type'   =>  explode('\\', get_class($new))[count(explode('\\', get_class($new)))-1],
            'target_id'     =>  $new->id,
            'associatedWith_type' => $new->belongsTo_type,
            'associatedWith_id' => $new->belongsTo_id,
            'action'        => 'create'
        ]);

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

        parent::delete();
    }

    /**
     * @param array $options
     * @return bool
     */
    public function save(array $options = [])
    {

        if (!in_array('silent', $options)) {
            Log::create([
                'target_type' => explode('\\', get_class($this))[count(explode('\\', get_class($this))) - 1],
                'target_id' => $this->id,
                'associatedWith_type' => $this->belongsTo_type,
                'associatedWith_id' => $this->belongsTo_id,
                'action' => 'update'
            ]);
        }

        if (!in_array('no_new_name', $options)) {
            $this->name = 'CS_';
            if (!is_null($this->belongsTo_type) && !is_null($this->belongsTo_id)) {
                $this->name .= $this->belongsTo_object()->name;
            }
            $this->name .= '_' . Carbon::parse($this->created_at)->format('y-m-d_h:i:s');
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
            if ($this->belongsTo_object()->notifications_enabled !== true) {
                return;
            }
        }

        foreach (User::get() as $u) {
            if ($u->setting('notifications_enabled') == 'on') {
                $u->message(trans('user.messages.critical_state_notification'));
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
            if ($this->belongsTo_object()->notifications_enabled !== true) {
                return;
            }
        }

        foreach (User::get() as $u) {
            if ($u->setting('notifications_enabled') == 'on') {
                $u->message(trans('user.messages.critical_state_notification_recovered'));
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
     * @return null
     */
    public function belongsTo_object()
    {
        if (!is_null($this->belongsTo_type) && !is_null($this->belongsTo_id)) {
            return ('App\\' . $this->belongsTo_type)::find($this->belongsTo_id);
        }

        return null;
    }

    /**
     *
     */
    public function icon()
    {
        // TODO: Implement icon() method.
    }

    /**
     *
     */
    public function url()
    {
        // TODO: Implement url() method.
    }
}
