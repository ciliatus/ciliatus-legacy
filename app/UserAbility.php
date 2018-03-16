<?php

namespace App;

use App\Traits\Uuids;

/**
 * Class UserAbility
 * @package App
 */
class UserAbility extends CiliatusModel
{
    use Uuids;

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
        'grant_api-list:all',
        'grant_api-list_admin',
        'grant_api-read',
        'grant_api-read_admin',
        'grant_api-write:biography_entry',
        'grant_api-write:caresheet',
        'grant_api-write:animal',
        'grant_api-write:animal_feeding',
        'grant_api-write:animal_feeding_schedule',
        'grant_api-write:animal_weighing',
        'grant_api-write:animal_weighing_schedule',
        'grant_api-write:terrarium',
        'grant_api-write:reminder',
        'grant_api-write:sensorreading',
        'grant_api-write:pump',
        'grant_api-write:valve',
        'grant_api-write:physical_sensor',
        'grant_api-write:logical_sensor',
        'grant_api-write:logical_sensor_threshold',
        'grant_api-write:generic_component',
        'grant_api-write:generic_component_type',
        'grant_api-write:controlunit',
        'grant_api-write:file',
        'grant_api-write:file_property',
        'grant_api-write:user_self',       // edit own profile
        'grant_api-write:user_ability',
        'grant_api-write:user_telegram',
        'grant_api-write:action',
        'grant_api-write:action_sequence',
        'grant_api-write:action_sequence_schedule',
        'grant_api-write:action_sequence_trigger',
        'grant_api-write:action_sequence_intention',
        'grant_api-write:property',
        'grant_api-fetch:desired_states',
        'grant_api-evaluate:critical_state'
    ];

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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function properties()
    {
        return $this->hasMany('App\Property', 'belongsTo_id')->where('belongsTo_type', 'UserAbility');
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
        return 'security-account';
    }

    /**
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function url()
    {
        return url('user_abilities/' . $this->id);
    }

}
