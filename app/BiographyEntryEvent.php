<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class BiographyEntryEvent
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BiographyEntryEvent whereBelongsToId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BiographyEntryEvent whereBelongsToType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BiographyEntryEvent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BiographyEntryEvent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BiographyEntryEvent whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BiographyEntryEvent whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BiographyEntryEvent whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BiographyEntryEvent whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BiographyEntryEvent whereValueJson($value)
 * @mixin \Eloquent
 */
class BiographyEntryEvent extends Event
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
            $builder->where('type', 'BiographyEntry');
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function files()
    {
        return $this->morphToMany('App\File', 'belongsTo', 'has_files', 'belongsTo_id', 'file_id');
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
        return url('biography_entries/' . $this->id);
    }
}
