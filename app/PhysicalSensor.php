<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PhysicalSensor
 * @package App
 */
class PhysicalSensor extends Model
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|null
     */
    public function terrarium()
    {
        if ($this->belongsTo_type == 'terrarium')
            return $this->belongsTo('App\Terrarium', 'id', 'belongsTo_id');

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
        $this->save();
    }
}
