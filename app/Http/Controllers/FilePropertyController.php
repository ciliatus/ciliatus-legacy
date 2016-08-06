<?php

namespace App\Http\Controllers;

use App\FileProperty;
use App\Http\Transformers\FilePropertyTransformer;
use Cache;
use Gate;
use Illuminate\Http\Request;


/**
 * Class FilePropertyController
 * @package App\Http\Controllers
 */
class FilePropertyController extends ApiController
{
    /**
     * @var FilePropertyTransformer
     */
    protected $file_propertyTransformer;

    /**
     * FilePropertyController constructor.
     * @param FilePropertyTransformer $_file_propertyTransformer
     */
    public function __construct(FilePropertyTransformer $_file_propertyTransformer)
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

        $file_propertys = FileProperty::paginate(10);

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

        $file_property = FileProperty::with('properties')->find($id);

        if (!$file_property) {
            return $this->respondNotFound('FileProperty not found');
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

        if (Gate::denies('api-write:file_property')) {
            return $this->respondUnauthorized();
        }

        $data = ;

        $file_property = FileProperty::find($data['propertys_id']);
        if (is_null($file_property)) {
            return $this->respondNotFound('FileProperty not found');
        }

        $file_property->delete();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('file_propertys'),
                'delay' => 2000
            ]
        ]);

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {

        if (Gate::denies('api-write:file_property')) {
            return $this->respondUnauthorized();
        }

        $data = ;

        $file_property = FileProperty::create();
        $file_property->name = $data['property_name'];
        $file_property->save();

        return $this->setStatusCode(200)->respondWithData(
            [
                'id'    =>  $file_property->id
            ],
            [
                'redirect' => [
                    'uri'   => url('file_propertys/' . $file_property->id . '/edit'),
                    'delay' => 100
                ]
            ]
        );

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {

        if (Gate::denies('api-write:file_property')) {
            return $this->respondUnauthorized();
        }

        $file_property = FileProperty::find($request->input('property_id'));
        if (is_null($file_property)) {
            return $this->respondNotFound('FileProperty not found');
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
