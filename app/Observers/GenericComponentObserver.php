<?php

namespace App\Observers;


use App\GenericComponent;

/**
 * Class GenericComponentObserver
 * @package App\Observers
 */
class GenericComponentObserver
{

    /**
     * @param GenericComponent $genericComponent
     * @return void
     */
    public function deleting(GenericComponent $genericComponent)
    {
        $genericComponent->states()->delete();
        $genericComponent->intentions()->delete();
    }
}