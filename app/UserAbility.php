<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserAbility
 * @package App
 */
class UserAbility extends CiliatusModel
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
        'name'
    ];

    /**
     * @var array
     */
    protected static $abilities = [
        'grant_admin',
        'grant_api-list',
        'grant_api-list:raw',
        'grant_api-list_admin',
        'grant_api-read',
        'grant_api-read_admin',
        'grant_api-write:caresheet',
        'grant_api-write:animal',
        'grant_api-write:animal_feeding',
        'grant_api-write:animal_feeding_schedule',
        'grant_api-write:animal_weighing',
        'grant_api-write:animal_weighing_schedule',
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
        'grant_api-write:user_ability',
        'grant_api-write:user_telegram',
        'grant_api-write:action',
        'grant_api-write:action_sequence',
        'grant_api-write:action_sequence_schedule',
        'grant_api-write:property',
        'grant_api-fetch:desired_states',
        'grant_api-evaluate:critical_state'
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
            'associatedWith_type' => 'User',
            'associatedWith_id' => $new->user_id,
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
            'associatedWith_type' => 'User',
            'associatedWith_id' => $this->user_id,
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
