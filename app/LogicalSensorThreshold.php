<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class LogicalSensorThreshold
 * @package App
 */
class LogicalSensorThreshold extends CiliatusModel
{
    use Traits\Uuids;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */

    public $incrementing = false;

    /**
     * @param array $attributes
     * @return CiliatusModel|LogicalSensorThreshold
     */
    public static function create(array $attributes = [])
    {
        $new = parent::create($attributes);
        Log::create([
            'target_type'   =>  explode('\\', get_class($new))[count(explode('\\', get_class($new)))-1],
            'target_id'     =>  $new->id,
            'associatedWith_type' => explode('\\', get_class($new))[count(explode('\\', get_class($new)))-1],
            'associatedWith_id' => $new->id,
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
            'associatedWith_type' => explode('\\', get_class($this))[count(explode('\\', get_class($this)))-1],
            'associatedWith_id' => $this->id,
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
                'associatedWith_type' => explode('\\', get_class($this))[count(explode('\\', get_class($this))) - 1],
                'associatedWith_id' => $this->id,
                'action' => 'update'
            ]);
        }

        return parent::save($options);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function logical_sensor()
    {
        return $this->belongsTo('App\LogicalSensor');
    }

    /**
     * @return string
     */
    public function icon()
    {
        return 'circle-o';
    }

    /**
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function url()
    {
        return url('logical_sensor_thresholds/' . $this->id);
    }
}
