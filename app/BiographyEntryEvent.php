<?php

namespace App;

use App\Events\BiographyEntryEventDeleted;
use App\Events\BiographyEntryEventUpdated;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;

/**
 * Class BiographyEntryEvent
 * @package App
 */
class BiographyEntryEvent extends Event
{
    use Uuids, Notifiable;

    /**
     * @var string
     */
    protected $table = 'events';

    /**
     * @var array
     */
    protected $dispatchesEvents = [
        'updated' => BiographyEntryEventUpdated::class,
        'deleting' => BiographyEntryEventDeleted::class
    ];

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
        return 'notebook';
    }

    /**
     * @return string
     */
    public function url()
    {
        return url('biography_entries/' . $this->id);
    }
}
