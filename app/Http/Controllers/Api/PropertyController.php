<?php

namespace App\Http\Controllers\Api;

use App\Property;
use App\Http\Transformers\PropertyTransformer;
use Cache;
use Gate;
use Illuminate\Http\Request;


/**
 * Class PropertyController
 * @package App\Http\Controllers
 */
class PropertyController extends ApiController
{
    /**
     * @var PropertyTransformer
     */
    protected $propertyTransformer;

    /**
     * PropertyController constructor.
     * @param PropertyTransformer $_propertyTransformer
     */
    public function __construct(PropertyTransformer $_propertyTransformer)
    {
        parent::__construct();
        $this->propertyTransformer = $_propertyTransformer;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if (Gate::denies('api-list')) {
            return $this->respondUnauthorized();
        }

        $properties = Property::query();

        $properties = $this->filter($request, $properties);

        /*
         * If raw is passed, pagination will be ignored
         * Permission api-list:raw is required
         */
        if ($request->has('raw') && Gate::allows('api-list:raw')) {

            return $this->setStatusCode(200)->respondWithData(
                $this->propertyTransformer->transformCollection(
                    $properties->get()->toArray()
                )
            );
        }

        $properties = $properties->paginate(env('PAGINATION_PER_PAGE', 100));

        return $this->setStatusCode(200)->respondWithPagination(
            $this->propertyTransformer->transformCollection(
                $properties->toArray()['data']
            ),
            $properties
        );
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

        $property = Property::with('properties')->find($id);

        if (!$property) {
            return $this->respondNotFound('Property not found');
        }

        return $this->setStatusCode(200)->respondWithData(
            $this->propertyTransformer->transform(
                $property->toArray()
            )
        );
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy()
    {

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {

        if (Gate::denies('api-write:property')) {
            return $this->respondUnauthorized();
        }

        $property = Property::find($request->input('property_id'));
        if (is_null($property)) {
            return $this->respondNotFound('Property not found');
        }

        $property->name = $request->input('property_name');

        $property->save();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('propertys'),
                'delay' => 1000
            ]
        ]);

    }

}
