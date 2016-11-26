<?php

namespace App;

use App\Events\PhysicalSensorDeleted;
use App\Events\PhysicalSensorUpdated;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PhysicalSensor
 * @package App
 */
class PhysicalSensor extends CiliatusModel
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
     * @param array $attributes
     * @return CiliatusModel|PhysicalSensor
     */
    public static function create(array $attributes = [])
    {
        $new = parent::create($attributes);
        Log::create([
            'target_type'   =>  explode('\\', get_class($new))[count(explode('\\', get_class($new)))-1],
            'target_id'     =>  $new->id,
            'associatedWith_type' => 'Terrarium',
            'associatedWith_id' => $new->terrarium_id,
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
            'associatedWith_type' => 'Terrarium',
            'associatedWith_id' => $this->terrarium_id,
            'action'        => 'delete'
        ]);

        broadcast(new PhysicalSensorDeleted($this));

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
                'associatedWith_type' => explode('\\', get_class($this))[count(explode('\\', get_class($this))) - 1],
                'associatedWith_id' => $this->id,
                'action' => 'update'
            ]);
        }

        broadcast(new PhysicalSensorUpdated($this));

        return parent::save($options);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|null
     */
    public function terrarium()
    {
        if ($this->belongsTo_type == 'terrarium')
            return $this->belongsTo('App\Terrarium', 'belongsTo_id', 'id');

        return null;
    }

    /**
     * @return null
     */
    public function belongsTo_object()
    {
        if (!is_null($this->belongsTo_type) && !is_null($this->belongsTo_id)) {
            return ('App\\' . ucfirst($this->belongsTo_type))::find($this->belongsTo_id);
        }

        return null;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logical_sensors()
    {
        return $this->hasMany('App\LogicalSensor');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function controlunit()
    {
        return $this->belongsTo('App\Controlunit');
    }

    /**
     * @return string
     */
    public function generateConfig()
    {
        $lsstr = [];
        foreach ($this->logical_sensors as $ls) {
            $lsstr[] = "{$ls->type}:{$ls->id}";
        }
        $lsstr = implode('|', $lsstr);

        $config = "[sensor_{$this->name}]\nid = {$this->id}\npin =\nname = {$this->name}\nmodel = {$this->model}\nlogical = {$lsstr}\nenabled = True";

        return $config;
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
        $this->save(['silent']);
    }

    /**
     * @return bool
     */
    public function check_notifications_enabled()
    {
        if (is_null($this->belongsTo_object()))
            return false;

        return $this->belongsTo_object()->check_notifications_enabled();
    }

    /**
     * @return string
     */
    public function icon()
    {
        return 'memory';
    }

    /**
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function url()
    {
        return url('physical_sensors/' . $this->id);
    }
}
