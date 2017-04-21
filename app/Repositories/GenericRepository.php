<?php

namespace App\Repositories;

use App\CiliatusModel;


/**
 * Class GenericRepository
 * @package App\Repositories
 */
class GenericRepository extends Repository
{


    /**
     * GenericRepository constructor.
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
            $this->addCiliatusSpecificFields();
        }

        return $model;
    }

}