<?php

namespace App\Observers;


use App\CustomComponentType;

/**
 * Class CustomComponentTypeObserver
 * @package App\Observers
 */
class CustomComponentTypeObserver
{

    /**
     * @param CustomComponentType $customComponentType
     * @return void
     */
    public function deleting(CustomComponentType $customComponentType)
    {
        $customComponentType->components()->delete();
        $customComponentType->states()->delete();
        $customComponentType->intentions()->delete();
    }
}