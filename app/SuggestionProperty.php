<?php

namespace App;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class SuggestionProperty
 * @package App
 */
class SuggestionProperty extends Property
{
    use Uuids;

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
            $builder->where('type', 'Suggestion');
        });
    }

    /**
     * @return string
     */
    public function icon()
    {
        return 'lightbulb_outline';
    }

    /**
     * @return string
     */
    public function url()
    {
        return url('suggestions/' . $this->id);
    }
}
