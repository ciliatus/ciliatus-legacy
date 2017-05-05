<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LogicalSensorThreshold
 *
 * @package App
 * @property string $id
 * @property string $name
 * @property string $logical_sensor_id
 * @property float $rawvalue_lowerlimit
 * @property float $rawvalue_upperlimit
 * @property string $starts_at
 * @property bool $active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\LogicalSensor $logical_sensor
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @method static \Illuminate\Database\Query\Builder|\App\LogicalSensorThreshold whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LogicalSensorThreshold whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LogicalSensorThreshold whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LogicalSensorThreshold whereLogicalSensorId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LogicalSensorThreshold whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LogicalSensorThreshold whereRawvalueLowerlimit($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LogicalSensorThreshold whereRawvalueUpperlimit($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LogicalSensorThreshold whereStartsAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LogicalSensorThreshold whereUpdatedAt($value)
 * @mixin \Eloquent
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
     * @var array
     */
    protected $casts = [
        'active'    =>  'boolean'
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'logical_sensor_id', 'starts_at', 'rawvalue_lowerlimit', 'rawvalue_upperlimit', 'active'
    ];

    /**
     * @param array $options
     * @return bool
     */
    public function save(array $options = [])
    {
        if (!in_array('no_new_name', $options)) {
            $this->name = 'LSTH_';
            if (!is_null($this->logical_sensor)) {
                $this->name .= $this->logical_sensor->name;
            } else {
                $this->name .= $this->id;
            }
            $this->name .= '_' . Carbon::parse($this->starts_at)->format('h:i:s');
            $this->save(['no_new_name']);
        }

        return parent::save($options);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function properties()
    {
        return $this->hasMany('App\Property', 'belongsTo_id')->where('belongsTo_type', 'LogicalSensorThreshold');
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
        return 'vertical_align_center';
    }

    /**
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function url()
    {
        return url('logical_sensor_thresholds/' . $this->id);
    }
}
