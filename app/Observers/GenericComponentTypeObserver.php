<?php

namespace App\Observers;


use App\GenericComponentType;

/**
 * Class GenericComponentTypeObserver
 * @package App\Observers
 */
class GenericComponentTypeObserver
{

    /**
     * @param GenericComponentType $genericComponentType
     * @return void
     */
    public function deleting(GenericComponentType $genericComponentType)
    {
        $genericComponentType->components()->delete();
        $genericComponentType->states()->delete();
        $genericComponentType->intentions()->delete();
    }
}