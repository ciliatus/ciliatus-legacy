<?php

namespace App\Http\Controllers;

use App\Http\Transformers\TerrariumTransformer;
use App\Terrarium;
use Gate;
use Illuminate\Http\Request;

use App\Http\Requests;


/**
 * Class TerrariumController
 * @package App\Http\Controllers
 */
class TerrariumController extends ApiController
{
    /**
     * @var TerrariumTransformer
     */
    protected $terrariumTransformer;

    /**
     * TerrariumController constructor.
     * @param TerrariumTransformer $_terrariumTransformer
     */
    public function __construct(TerrariumTransformer $_terrariumTransformer)
    {
        parent::__construct();
        $this->terrariumTransformer = $_terrariumTransformer;
    }

    public function index()
    {
        if (Gate::denies('api-list')) {
            return $this->respondUnauthorized();
        }

        $terraria = Terrarium::paginate(10);

        return $this->setStatusCode(200)->respondWithPagination($this->terrariumTransformer->transformCollection($terraria->toArray()['data']), $terraria);
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

        $terrarium = Terrarium::find($id)->with('physical_sensors', 'animals')
                                         ->get()->first();

        if (!$terrarium) {
            return $this->respondNotFound('Terrarium not found');
        }

        return $this->setStatusCode(200)->respondWithData($this->terrariumTransformer->transform($terrarium->toArray()));
    }

}
