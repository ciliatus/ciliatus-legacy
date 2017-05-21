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
     * @var array
     */
    protected $show_parameters = [];

    /**
     * Adds properties, icon and url fields
     */
    protected function addCiliatusSpecificFields()
    {
        $this->scope->properties = $this->scope->properties()->get();
        $this->scope->icon = $this->scope->icon();
        $this->scope->url = $this->scope->url();
    }

    /**
     * Adds a parameter to be used when calling show method.
     * Useful when using a generic show() call (e.g. in
     * ApiController->respondTransformedAndPaginated())
     *
     * @param $name
     * @param $value
     */
    public function addShowParameter($name, $value)
    {
        $this->show_parameters[$name] = $value;
    }

}