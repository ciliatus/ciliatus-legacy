<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class AnimalFeedingEvent
 *
 * @property string $id
 * @property string $belongsTo_type
 * @property string $belongsTo_id
 * @property string $type
 * @property string $name
 * @property string $value
 * @property string $value_json
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalFeedingEvent whereBelongsToId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalFeedingEvent whereBelongsToType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalFeedingEvent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalFeedingEvent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalFeedingEvent whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalFeedingEvent whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalFeedingEvent whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalFeedingEvent whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalFeedingEvent whereValueJson($value)
 * @mixin \Eloquent
 */
class AnimalFeedingEvent extends Event
{
    use Traits\Uuids;

    /**
     * @var string
     */
    protected $table = 'events';

    /**
     *
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('type', function (Builder $builder) {
            $builder->where('type', 'AnimalFeeding');
        });
    }

    /**
     * @return string
     */
    public function icon()
    {
        return 'event';
    }

    /**
     * @return string
     */
    public function url()
    {
        return url('animal_feedings/' . $this->id);
    }
}
