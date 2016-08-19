<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserAbility
 * @package App
 */
class UserAbility extends Model
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
        'ability'
    ];

    /**
     * @var array
     */
    protected static $abilities = [
        'grant_api-list',
        'grant_api-list_admin',
        'grant_api-read',
        'grant_api-read_admin',
        'grant_api-write:animal',
        'grant_api-write:terrarium',
        'grant_api-write:sensorreading',
        'grant_api-write:pump',
        'grant_api-write:valve',
        'grant_api-write:physical_sensor',
        'grant_api-write:logical_sensor',
        'grant_api-write:logical_sensor_threshold',
        'grant_api-write:controlunit',
        'grant_api-write:file',
        'grant_api-write:file_property',
        'grant_api-write:user_self',       // edit own profile
        'grant_api-write:user_all',         // edit all users including permissions and protected properties
        'grant_api-write:user_ability',
        'grant_api-write:user_telegram',
        'grant_api-write:actions',
        'grant_api-write:action_sequences',
        'grant_api-write:action_sequence_schedules',
        'grant_api-fetch:desired_states'
    ];

    /**
     * @param array $attributes
     * @return Model|UserAbility
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
        if (!in_array($this->name, self::$abilities)) {
            return false;
        }

        return parent::save($options);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * @return array
     */
    public static function abilities()
    {
        return self::$abilities;
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
        return url('user_abilities/' . $this->id);
    }

}
