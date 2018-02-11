<?php

namespace App\Observers;


use App\Terrarium;

/**
 * Class TerrariumObserver
 * @package App\Observers
 */
class TerrariumObserver
{
    /**
     * @param  Terrarium $terrarium
     * @return void
     */
    public function saving(Terrarium $terrarium)
    {
        $terrarium->updateStaticFields();
    }

    /**
     * @param  Terrarium $terrarium
     * @return void
     */
    public function deleting(Terrarium $terrarium)
    {
        $terrarium->action_sequences()->delete();

        foreach ($terrarium->animals as $a) {
            $a->terrarium_id = null;
            $a->save();
        }

        foreach ($terrarium->valves as $v) {
            $v->terrarium_id = null;
            $v->save();
        }

        foreach ($terrarium->physical_sensors as $ps) {
            $ps->setBelongsTo();
        }

        foreach ($terrarium->generic_components as $gc) {
            $gc->setBelongsTo();
        }
    }
}