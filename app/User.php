<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Carbon\Carbon;
use Laravel\Passport\HasApiTokens;

/**
 * Class User
 *
 * @package App
 * @property string $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $locale
 * @property string $timezone
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\UserAbility[] $abilities
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\UserSetting[] $settings
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @method static \Illuminate\Database\Query\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereLocale($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereTimezone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends CiliatusModel implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, HasApiTokens, CanResetPassword, Traits\Uuids;

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
        'name', 'email', 'password', 'locale'
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
     * @param array $attributes
     * @return CiliatusModel|User
     */
    public static function create(array $attributes = [])
    {
        $new = new User($attributes);
        $new->save();

        Log::create([
            'target_type'   =>  explode('\\', get_class($new))[count(explode('\\', get_class($new)))-1],
            'target_id'     =>  $new->id,
            'associatedWith_type' => explode('\\', get_class($new))[count(explode('\\', get_class($new)))-1],
            'associatedWith_id' => $new->id,
            'action'        => 'create'
        ]);

        return $new;
    }

    /**
     *
     */
    public function delete()
    {
        Log::create([
            'target_type'   =>  explode('\\', get_class($this))[count(explode('\\', get_class($this)))-1],
            'target_id'     =>  $this->id,
            'associatedWith_type' => explode('\\', get_class($this))[count(explode('\\', get_class($this)))-1],
            'associatedWith_id' => $this->id,
            'action'        => 'delete'
        ]);

        foreach ($this->abilities as $ability) {
            $ability->delete();
        }

        foreach ($this->settings as $setting) {
            $setting->delete();
        }

        parent::delete();
    }

    /**
     * @param array $options
     * @return bool
     */
    public function save(array $options = [])
    {

        if (!in_array('silent', $options)) {
            Log::create([
                'target_type' => explode('\\', get_class($this))[count(explode('\\', get_class($this))) - 1],
                'target_id' => $this->id,
                'associatedWith_type' => explode('\\', get_class($this))[count(explode('\\', get_class($this))) - 1],
                'associatedWith_id' => $this->id,
                'action' => 'update'
            ]);
        }

        return parent::save($options);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function properties()
    {
        return $this->hasMany('App\Property', 'belongsTo_id')->where('belongsTo_type', 'User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function settings()
    {
        return $this->hasMany('App\UserSetting');
    }

    /**
     * @param $name
     * @return null
     */
    public function setting($name)
    {
        $setting = $this->settings()->where('name', $name)->first();
        if (is_null($setting))
            return null;

        return $setting->value;
    }

    /**
     * @param $id
     * @return null
     */
    public function settingById($id)
    {
        $setting = $this->settings()->where('id', $id)->first();
        if (is_null($setting))
            return null;

        return $setting->value;
    }

    /**
     * @param $name
     * @return null
     */
    public function settingId($name)
    {
        $setting = $this->settings()->where('name', $name)->first();
        if (is_null($setting))
            return null;

        return $setting->id;
    }

    /**
     * @param $name
     * @param $value
     */
    public function setSetting($name, $value)
    {
        $setting = $this->settings()->where('name', $name)->first();
        if (is_null($setting))
            $setting = UserSetting::create(['user_id' => $this->id, 'name' => $name]);

        $setting->value = $value;
        $setting->save();

    }

    /**
     * @param $name
     */
    public function deleteSetting($name)
    {
        $setting = $this->settings()->where('name', $name)->first();
        if (!is_null($setting))
            $setting->delete();
    }

    /**
     * @param $id
     */
    public function deleteSettingById($id)
    {
        $this->settings()->where('id', $id)->delete();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function abilities()
    {
        return $this->hasMany('App\UserAbility');
    }

    /**
     * Alias for hasAbility()
     *
     * @param $name
     * @return bool
     */
    public function ability($name)
    {
        return $this->hasAbility($name);
    }

    /**
     * @param $ability
     * @return bool
     */
    public function hasAbility($ability)
    {
        return $this->abilities()->where('name', $ability)->get()->count() > 0;
    }

    /**
     *
     */
    public function grantFullAbilities()
    {
        foreach (UserAbility::abilities() as $a) {
            $ua = $this->abilities()->where('name', $a)->first();
            if (is_null($ua)) {
                $ua = UserAbility::create(['user_id' => $this->id]);
                $ua->name = $a;
                $ua->save();
            }
        }
    }

    /**
     * Return current time converted
     * to user's timezone
     *
     * @return Carbon
     */
    public function time()
    {
        if (is_null($this->timezone))
            return Carbon::now();

        return Carbon::now()->setTimezone($this->timezone);
    }

    /**
     * Return true if it's night time in
     * the user's current timezone
     *
     * @return bool
     */
    public function night()
    {
        if (is_null($this->setting('night_starts_at'))) {
            $night_start = $this->time();
            $night_start->hour = 20;
            $night_start->minute = 0;
            $night_start->second = 0;
        }
        else {
            $night_start = Carbon::parse($this->setting('night_starts_at'));
        }


        if (is_null($this->setting('night_ends_at'))) {
            $night_end = $this->time();
            $night_end->hour = 8;
            $night_end->minute = 0;
            $night_end->second = 0;
        }
        else {
            $night_end = Carbon::parse($this->setting('night_ends_at'));
        }

        return !$this->time()->between($night_start, $night_end);
    }

    /**
     * @param $content
     * @return bool
     */
    public function message($content)
    {
        if (is_null($this->setting('notification_type'))) {
            return false;
        }

        if ($this->setting('notifications_enabled') != 'on') {
            return false;
        }

        try {
            $message = Message::create([
                'type' => $this->setting('notification_type'),
                'user_id' => $this->id
            ]);
        }
        catch (\InvalidArgumentException $ex) {
            \Log::error('Invalid message type for user ' . $this->name . ': ' . $this->setting('notification_type') . '. Exception: ' . $ex->getMessage());
            return false;
        }

        if (is_null($message)) {
            \Log::error('Message type missing');
            return false;
        }

        $message->content = $content;

        return $message->send();
    }

    /**
     * @return string
     */
    public function icon()
    {
        return 'person';
    }

    /**
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function url()
    {
        return url('users/' . $this->id);
    }

}