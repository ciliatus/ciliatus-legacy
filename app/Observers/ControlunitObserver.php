<?php

namespace App\Observers;


use App\Controlunit;

class ControlunitObserver
{

    /**
     * @param Controlunit $controlunit
     * @return void
     */
    public function deleting(Controlunit $controlunit)
    {
        foreach ($controlunit->physical_sensors as $ps) {
            $ps->controlunit_id = null;
            $ps->save();
        }

        foreach ($controlunit->pumps as $p) {
            $p->controlunit_id = null;
            $p->save();
        }

        foreach ($controlunit->valves as $v) {
            $v->controlunit_id = null;
            $v->save();
        }

        foreach ($controlunit->generic_components as $gc) {
            $gc->controlunit_id = null;
            $gc->save();
        }

        foreach ($controlunit->critical_states as $cs) {
            $cs->setBelongsTo();
        }
    }
}