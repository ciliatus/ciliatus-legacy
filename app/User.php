<?php

namespace App;

use App\Traits\Uuids;
use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * Class User
 * @package App
 */
class User extends CiliatusModel implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, HasApiTokens, CanResetPassword, Uuids, Notifiable;

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
     * Returns an Eloquent Builder filtered for users with enabled notifications
     * If $type is set an additional filter for the setting defined in $type will be added
     *
     * @param null $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function getWhereNotificationsEnabled($type = null)
    {
        $query = User::query()->with('settings');
        $query->whereHas('settings', function ($query) {
            $query->where('name', 'notifications_enabled')
                  ->where('value', 'on');
        });

        if (!is_null($type)) {
            $query->whereHas('settings', function ($query) use ($type) {
                $query->where('name', 'notifications_' . $type . '_enabled')
                      ->where('value', 'on');
            });
        }

        return $query;
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

        \Log::info('Message => ' . $this->id . ': ' . $message->content);

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