<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 * @package App
 */
class User extends Authenticatable
{
    use Traits\Uuids;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function abilities()
    {
        return $this->hasMany('App\UserAbility');
    }

    /**
     * @param $ability
     * @return bool
     */
    public function hasAbility($ability)
    {
        return $this->abilities()->where('name', $ability)->get()->count() > 0;
    }

    public function grantFullAbilities()
    {
        $abilities = [
            'grant_api-list',
            'grant_api-read',
            'grant_api-write:animal',
            'grant_api-write:terrarium',
            'grant_api-write:sensorreading',
            'grant_api-write:pump',
            'grant_api-write:valve',
            'grant_api-write:physical_sensor',
            'grant_api-write:logical_sensor',
            'grant_api-write:controlunit'
        ];

        foreach ($abilities as $a) {
            $ua = $this->abilities()->where('name', $a)->first();
            if (is_null($ua)) {
                $ua = UserAbility::create(['user_id' => $this->id]);
                $ua->name = $a;
                $ua->save();
            }
        }
    }

}