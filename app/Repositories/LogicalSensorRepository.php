<?php

namespace App\Repositories;

use App\CiliatusModel;


/**
 * Class LogicalSensorRepository
 * @package App\Repositories
 */
class LogicalSensorRepository extends Repository
{


    /**
     * LogicalSensorRepository constructor.
     * @param CiliatusModel|null $scope
     */
    public function __construct(CiliatusModel $scope = null)
    {

        $this->scope = $scope;
        $this->addCiliatusSpecificFields();

    }

    /**
     * @return CiliatusModel
     */
    public function show()
    {
        $model = $this->scope;

        if (!is_null($model)) {
            $model->current_threshold_id = is_null($model->current_threshold()) ? null : $model->current_threshold()->id;
        }

        return $model;
    }

}