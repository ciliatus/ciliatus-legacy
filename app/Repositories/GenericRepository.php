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
        $this->addCiliatusSpecificFields();

    }

    /**
     * @return CiliatusModel
     */
    public function show()
    {
        $model = $this->scope;

        return $model;
    }

}