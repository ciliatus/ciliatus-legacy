<?php

namespace App\Http\Controllers;

use App\Http\Transformers\AnimalTransformer;
use App\Animal;
use Gate;
use Illuminate\Http\Request;

use App\Http\Requests;


/**
 * Class AnimalController
 * @package App\Http\Controllers
 */
class AnimalController extends ApiController
{
    /**
     * @var AnimalTransformer
     */
    protected $animalTransformer;

    /**
     * AnimalController constructor.
     * @param AnimalTransformer $_animalTransformer
     */
    public function __construct(AnimalTransformer $_animalTransformer)
    {
        parent::__construct();
        $this->animalTransformer = $_animalTransformer;
    }

    public function index()
    {
        if (Gate::denies('api-list')) {
            return $this->respondUnauthorized();
        }

        $animals = Animal::paginate(10);

        return $this->setStatusCode(200)->respondWithPagination($this->animalTransformer->transformCollection($animals->toArray()['data']), $animals);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {

        if (Gate::denies('api-read')) {
            return $this->respondUnauthorized();
        }

        $animal = Animal::find($id);

        if (!$animal) {
            return $this->respondNotFound('Animal not found');
        }

        return $this->setStatusCode(200)->respondWithData($this->animalTransformer->transform($animal->toArray()));
    }

}
