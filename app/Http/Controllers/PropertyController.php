<?php

namespace App\Http\Controllers;

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
    protected $file_propertyTransformer;

    /**
     * PropertyController constructor.
     * @param PropertyTransformer $_file_propertyTransformer
     */
    public function __construct(PropertyTransformer $_file_propertyTransformer)
    {
        parent::__construct();
        $this->file_propertyTransformer = $_file_propertyTransformer;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        if (Gate::denies('api-list')) {
            return $this->respondUnauthorized();
        }

        $file_propertys = Property::paginate(10);

        return $this->setStatusCode(200)->respondWithPagination(
            $this->file_propertyTransformer->transformCollection(
                $file_propertys->toArray()['data']
            ),
            $file_propertys
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

        $file_property = Property::with('properties')->find($id);

        if (!$file_property) {
            return $this->respondNotFound('Property not found');
        }

        return $this->setStatusCode(200)->respondWithData(
            $this->file_propertyTransformer->transform($file_property->toArray())
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

        if (Gate::denies('api-write:file_property')) {
            return $this->respondUnauthorized();
        }

        $file_property = Property::find($request->input('property_id'));
        if (is_null($file_property)) {
            return $this->respondNotFound('Property not found');
        }

        $file_property->name = $request->input('property_name');

        $file_property->save();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('file_propertys'),
                'delay' => 1000
            ]
        ]);

    }

}
