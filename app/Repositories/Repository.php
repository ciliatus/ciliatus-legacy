<?php

namespace App\Repositories;
use App\CiliatusModel;
use App\Factories\RepositoryFactory;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Illuminate\Support\Collection;

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

        if (!in_array('api_base_url', $exclude))
            $this->scope->api_base_url = $this->scope->api_base_url();

        if (!in_array('web_base_url', $exclude))
            $this->scope->web_base_url = $this->scope->web_base_url();

        if (!in_array('url', $exclude))
            $this->scope->url = $this->scope->url();

        if (!in_array('active', $exclude))
            $this->scope->active = $this->scope->active();

        if (!in_array('class', $exclude)) {
            $class_split = explode("\\", get_class($this->scope));
            $this->scope->class = end($class_split);
        }

        $related_models = array_filter(
            $this->scope->getRelations(),
            function ($relation) {
                if (is_a($relation, MorphPivot::class)) {
                    return false;
                }

                return true;
            }
        );

        foreach ($related_models as $relation=>$objects) {
            if (is_a($objects, MorphPivot::class)) {
                unset($related_models[$relation]);
                dd($related_models);
                continue;
            }

            if (is_a($objects, Collection::class)) {
                foreach ($objects as $index=>$object) {
                    if (is_null($object)) {
                        unset($related_models[$relation][$index]);
                        continue;
                    }
                    $this->applyRepositoryToObject($object);
                }
            }
            else {
                $object = $objects;
                if (is_null($object)) {
                    unset($related_models[$relation]);
                    continue;
                }
                $this->applyRepositoryToObject($object);
            }
        }

        $this->scope->related_models = $related_models;
    }

    /**
     * @param CiliatusModel $object
     */
    protected function applyRepositoryToObject(CiliatusModel $object)
    {
        $repository = RepositoryFactory::get($object);
        $repository->show();
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