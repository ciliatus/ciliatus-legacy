<?php

namespace App\Repositories;

/**
 * Class Repository
 * @package App\Repositories
 */
abstract class Repository {

    /**
     * @var
     */
    protected $scope;

    /**
     * Adds properties, icon and url fields
     */
    protected function addCiliatusSpecificFields()
    {
        $this->scope->properties = $this->scope->properties()->get();
        $this->scope->icon = $this->scope->icon();
        $this->scope->url = $this->scope->url();
    }

}