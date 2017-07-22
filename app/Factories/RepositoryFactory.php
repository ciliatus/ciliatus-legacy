<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 16.07.2017
 * Time: 14:10
 */

namespace App\Factories;


use App\CiliatusModel;
use App\Repositories\GenericRepository;
use App\Repositories\Repository;

/**
 * Class RepositoryFactory
 * @package App\Factory
 */
class RepositoryFactory extends Factory
{

    /**
     * @param CiliatusModel $object
     * @return Repository
     */
    public static function get(CiliatusModel $object)
    {
        $class_name_arr = explode('\\', get_class($object));
        $class_name = end($class_name_arr);

        $repo_name = sprintf(
            'App\Repositories\%sRepository',
            $class_name
        );

        if (class_exists($repo_name)) {
            return new $repo_name($object);
        }

        \Log::debug('Returning generic repository for class ' . $class_name);
        return new GenericRepository($object);
    }

}