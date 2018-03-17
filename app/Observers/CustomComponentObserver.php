<?php

namespace App\Observers;


use App\CustomComponent;

/**
 * Class CustomComponentObserver
 * @package App\Observers
 */
class CustomComponentObserver
{

    /**
     * @param CustomComponent $customComponent
     * @return void
     */
    public function deleting(CustomComponent $customComponent)
    {
        $customComponent->states()->delete();
        $customComponent->intentions()->delete();
    }
}