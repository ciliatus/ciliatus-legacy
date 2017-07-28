<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class AnimalFeedingScheduleProperty
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalFeedingScheduleProperty whereBelongsToId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalFeedingScheduleProperty whereBelongsToType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalFeedingScheduleProperty whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalFeedingScheduleProperty whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalFeedingScheduleProperty whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalFeedingScheduleProperty whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalFeedingScheduleProperty whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalFeedingScheduleProperty whereValue($value)
 * @mixin \Eloquent
 */
class AnimalFeedingScheduleProperty extends Property
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
            $builder->where('type', 'AnimalFeedingSchedule');
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
        return url('animal_feeding_schedules/' . $this->id);
    }
}
