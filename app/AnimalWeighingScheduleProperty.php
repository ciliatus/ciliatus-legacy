<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class AnimalFeedingWeighingProperty
 *
 * @property string $id
 * @property string $belongsTo_type
 * @property string $belongsTo_id
 * @property string $type
 * @property string $name
 * @property bool $value
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Animal $animal
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalWeighingScheduleProperty whereBelongsToId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalWeighingScheduleProperty whereBelongsToType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalWeighingScheduleProperty whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalWeighingScheduleProperty whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalWeighingScheduleProperty whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalWeighingScheduleProperty whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalWeighingScheduleProperty whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalWeighingScheduleProperty whereValue($value)
 * @mixin \Eloquent
 */
class AnimalWeighingScheduleProperty extends Property
{
    use Traits\Uuids;

    /**
     * @var string
     */
    protected $table = 'properties';

    /**
     *
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('type', function (Builder $builder) {
            $builder->where('type', 'AnimalWeighingSchedule');
        });
    }

    public function animal()
    {
        return $this->belongsTo('App\Animal', 'belongsTo_id');
    }

    /**
     * @return string
     */
    public function icon()
    {
        return 'schedule';
    }

    /**
     * @return string
     */
    public function url()
    {
        return url('animal_weighing_schedules/' . $this->id);
    }
}
