<?php

namespace App\Observers;


use App\Animal;

/**
 * Class AnimalObserver
 * @package App\Observers
 */
class AnimalObserver
{
    /**
     * @param  Animal $animal
     * @return void
     */
    public function deleting(Animal $animal)
    {
        foreach ($animal->files as $f) {
            $f->setBelongsTo();
        }

        $animal->feeding_schedules()->delete();
        $animal->weighing_schedules()->delete();
        $animal->feedings()->delete();
        $animal->weighings()->delete();
        $animal->biography_entries()->delete();
    }
}