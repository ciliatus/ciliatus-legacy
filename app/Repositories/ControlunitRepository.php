<?php

namespace App\Repositories;

use App\Controlunit;
use Carbon\Carbon;

/**
 * Class ControlunitRepository
 * @package App\Repositories
 */
class ControlunitRepository extends Repository
{
    /**
     * TerrariumRepository constructor.
     * @param Controlunit $scope
     */
    public function __construct(Controlunit $scope = null)
    {

        $this->scope = $scope ? : new Controlunit;
        $this->addCiliatusSpecificFields();

    }

    /**
     * @return Controlunit
     */
    public function show()
    {
        /**
         * @var Controlunit $controlunit
         */
        $controlunit = $this->scope;

        $controlunit->heartbeat_critical = !$controlunit->heartbeatOk();
        $controlunit->state_ok = $controlunit->stateOk();

        return $controlunit;
    }

}