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
     *
     * @param array $exclude
     */
    protected function addCiliatusSpecificFields(array $exclude = ['properties'])
    {
        if (!in_array('properties', $exclude))
            $this->scope->properties = $this->scope->properties()->get();

        if (!in_array('icon', $exclude))
            $this->scope->icon = $this->scope->icon();

        if (!in_array('url', $exclude))
            $this->scope->url = $this->scope->url();

        if (!in_array('active', $exclude))
            $this->scope->active = $this->scope->active();
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