<?php

namespace App\Observers;


use App\Log;
use App\UserAbility;

class UserAbilityObserver
{
    /**
     * @param  UserAbility $userAbility
     * @return void
     */
    public function creating(UserAbility $userAbility)
    {
        Log::create([
            'target_type'   =>  'UserAbility',
            'target_id'     =>  $userAbility->id,
            'associatedWith_type' => 'User',
            'associatedWith_id' => $userAbility->user_id,
            'action'        => 'create'
        ]);
    }

    /**
     * @param  UserAbility $userAbility
     * @return void
     */
    public function deleting(UserAbility $userAbility)
    {
        Log::create([
            'target_type'   =>  'UserAbility',
            'target_id'     =>  $userAbility->id,
            'associatedWith_type' => 'User',
            'associatedWith_id' => $userAbility->user_id,
            'action'        => 'delete'
        ]);
    }
}