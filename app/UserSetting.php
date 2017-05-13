<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserSetting
 *
 * @package App
 * @property string $id
 * @property string $user_id
 * @property string $name
 * @property string $value
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\UserSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\UserSetting whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\UserSetting whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\UserSetting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\UserSetting whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\UserSetting whereValue($value)
 * @mixin \Eloquent
 */
class UserSetting extends CiliatusModel
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
    protected $fillable = [
        'user_id',
        'name',
        'value'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function properties()
    {
        return $this->hasMany('App\Property', 'belongsTo_id')->where('belongsTo_type', 'UserSetting');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * @return string
     */
    public function icon()
    {
        return 'user-plus';
    }

    /**
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function url()
    {
        return url('user_settings/' . $this->id);
    }
}
