<?php

namespace App;

use Carbon\Carbon;
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
     * @param array $attributes
     * @return Authenticatable|User
     */
    public static function create(array $attributes = [])
    {
        $new = parent::create($attributes);
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

    public function settingById($id)
    {
        $setting = $this->settings()->where('id', $id)->first();
        if (is_null($setting))
            return null;

        return $setting->value;
    }

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
        $night_start = $this->time();
        $night_start->hour = 20;
        $night_start->minute = 0;
        $night_start->second = 0;

        $night_end = $this->time();
        $night_end->hour = 8;
        $night_end->minute = 0;
        $night_end->second = 0;

        return !$this->time()->between($night_start, $night_end);
    }

    /**
     * @param $content
     * @return bool
     */
    public function message($content)
    {
        if (is_null($this->setting('notification_type')))
            return false;

        if ($this->setting('notifications_enabled') != 'on')
            return false;

        $message = Message::create($this->setting('notification_type'));
        $message->content = $content;
        $message->send();
    }

    /**
     * @return string
     */
    public function icon()
    {
        return 'user';
    }

    /**
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function url()
    {
        return url('users/' . $this->id);
    }

}