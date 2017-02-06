<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class System
 * @package App
 */
class System extends Model
{

    public static function status()
    {
        return [
            'emergency_stop' => !is_null(Property::where('type', 'SystemProperty')->where('name', 'stop_all_action_sequences')->get()->first())
        ];
    }

}
