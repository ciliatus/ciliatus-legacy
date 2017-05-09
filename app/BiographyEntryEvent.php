<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class BiographyEntryEvent
 *
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
