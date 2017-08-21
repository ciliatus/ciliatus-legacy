<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class SuggestionProperty
 *
 * @package App
 * @property string $id
 * @property string $belongsTo_type
 * @property string $belongsTo_id
 * @property string $type
 * @property string $name
 * @property bool $value
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SuggestionProperty whereBelongsToId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SuggestionProperty whereBelongsToType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SuggestionProperty whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SuggestionProperty whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SuggestionProperty whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SuggestionProperty whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SuggestionProperty whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SuggestionProperty whereValue($value)
 * @mixin \Eloquent
 */
class SuggestionProperty extends Property
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
            $builder->where('type', 'Suggestion');
        });
    }

    /**
     * @return string
     */
    public function icon()
    {
        return 'light_bulb';
    }

    /**
     * @return string
     */
    public function url()
    {
        return url('suggestions/' . $this->id);
    }
}
