<?php

namespace App\Observers;


use App\Room;

/**
 * Class RoomObserver
 * @package App\Observers
 */
class RoomObserver
{
    /**
     * @param Room $room
     * @return void
     */
    public function saving(Room $room)
    {
        $room->updateStaticFields();
    }

    /**
     * @param Room $room
     * @return void
     */
    public function deleting(Room $room)
    {
        foreach ($room->physical_sensors as $ps) {
            $ps->setBelongsTo();
        }
    }
}