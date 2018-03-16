<?php

namespace App;

use App\Events\BiographyEntryEventDeleted;
use App\Events\BiographyEntryEventUpdated;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;

/**
 * Class SuggestionEvent
 * @package App
 */
class SuggestionEvent extends Event
{
    use Uuids, Notifiable;

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
            $builder->where('type', 'Suggestion');
        });
    }

    /**
     * @return string
     */
    public function icon()
    {
        return 'lightbulb-on-outline';
    }

    /**
     * @return string
     */
    public function url()
    {
        return url('suggestions/' . $this->id);
    }
}
