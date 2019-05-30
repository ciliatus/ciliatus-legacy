<?php

namespace App\Repositories;

use App\LogicalSensor;
use App\Room;
use Carbon\Carbon;

/**
 * Class RoomRepository
 * @package App\Repositories
 */
class RoomRepository extends Repository
{
    /**
     * RoomRepository constructor.
     * @param Room $scope
     */
    public function __construct(Room $scope = null)
    {

        $this->scope = $scope ? : new Room();
        $this->addCiliatusSpecificFields();

    }

    /**
     * @param Carbon $history_to
     * @param null $history_minutes
     * @return Room
     */
    public function show(Carbon $history_to = null, $history_minutes = null)
    {
        /**
         * @var Room $room
         */
        $room = $this->scope;

        $this->addSensorSpecificFields($history_to, $history_minutes);

        $room->default_background_filepath = $room->background_image_path();

        return $room;
    }

}