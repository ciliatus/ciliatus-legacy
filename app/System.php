<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;

/**
 * Class System
 *
 * @package App
 * @mixin \Eloquent
 */
class System extends Model
{

    public static function status()
    {
        return [
            'emergency_stop' => !is_null(Property::where('type', 'SystemProperty')->where('name', 'stop_all_action_sequences')->get()->first())
        ];
    }

    public static function hasVoiceCapability()
    {
        return !is_null(env('API_AI_ACCESS_TOKEN_' . Auth::user()->lang, null));
    }

}
