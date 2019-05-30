<?php

namespace App\Repositories;

use App\LogicalSensor;
use App\Terrarium;
use Carbon\Carbon;

/**
 * Class TerrariumRepository
 * @package App\Repositories
 */
class TerrariumRepository extends Repository
{
    /**
     * TerrariumRepository constructor.
     * @param Terrarium $scope
     */
    public function __construct(Terrarium $scope = null)
    {

        $this->scope = $scope ? : new Terrarium();
        $this->addCiliatusSpecificFields();

    }

    /**
     * @param Carbon $history_to
     * @param null $history_minutes
     * @return Terrarium
     */
    public function show(Carbon $history_to = null, $history_minutes = null)
    {
        /**
         * @var Terrarium $terrarium
         */
        $terrarium = $this->scope;

        $this->addSensorSpecificFields($history_to, $history_minutes);

        $terrarium->default_background_filepath = $terrarium->background_image_path();
        $terrarium->capabilities = $terrarium->capabilities();

        return $terrarium;
    }

}