<?php

namespace App\Observers;


use App\Log;
use App\User;

class UserObserver
{
    /**
     * @param  User $user
     * @return void
     */
    public function creating(User $user)
    {
        Log::create([
            'target_type'   =>  'User',
            'target_id'     =>  $user->id,
            'associatedWith_type' => 'User',
            'associatedWith_id' => $user->id,
            'action'        => 'create'
        ]);
    }
    /**
     * @param  User $user
     * @return void
     */
    public function saved(User $user)
    {
        Log::create([
            'target_type' => 'User',
            'target_id' => $user->id,
            'associatedWith_type' => 'User',
            'associatedWith_id' => $user->id,
            'action' => 'update'
        ]);
    }

    /**
     * @param  User $user
     * @return void
     */
    public function deleting(User $user)
    {
        Log::create([
            'target_type'   =>  'User',
            'target_id'     =>  $user->id,
            'associatedWith_type' => 'User',
            'associatedWith_id' => $user->id,
            'action'        => 'delete'
        ]);

        $user->abilities()->delete();
        $user->settings()->delete();
    }
}