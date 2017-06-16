<?php

namespace App\Repositories;

use App\CiliatusModel;


/**
 * Class LogRepository
 * @package App\Repositories
 */
class LogRepository extends Repository
{


    /**
     * LogRepository constructor.
     * @param CiliatusModel|null $scope
     */
    public function __construct(CiliatusModel $scope = null)
    {

        $this->scope = $scope;

    }

    /**
     * @return CiliatusModel
     */
    public function show()
    {
        $model = $this->scope;

        if (!is_null($model)) {
            $model->addSourceTargetAssociated(true);
        }

        return $model;
    }

}