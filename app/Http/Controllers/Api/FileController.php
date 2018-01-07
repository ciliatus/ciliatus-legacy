<?php

namespace App\Http\Controllers\Api;

use App\File;
use App\Property;
use App\System;
use Auth;
use Gate;
use \Illuminate\Http\Request;


/**
 * Class FileController
 * @package App\Http\Controllers
 */
class FileController extends ApiController
{

    /**
     * FileController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return parent::default_index($request);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        return parent::default_show($request, $id);
    }


    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {

        if (Gate::denies('api-write:file')) {
            return $this->respondUnauthorized();
        }

        $file = File::find($id);
        if (is_null($file)) {
            return $this->respondNotFound('File not found');
        }

        $file->delete();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('files'),
                'delay' => 2000
            ]
        ]);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \ErrorException
     */
    public function store(Request $request)
    {

        if (Gate::denies('api-write:file')) {
            return $this->respondUnauthorized();
        }

        if (!$request->file('file')) {
            return $this->setStatusCode(422)->respondWithError('No file');
        }

        if ($request->file('file')->getClientSize() > System::maxUploadFileSize()) {
            return $this->setStatusCode(422)->respondWithError('File to big');
        }
        /*
         * Create file model
         */
        $file = File::createFromRequest($request, Auth::user()->id);

        /*
         * Look for optional inputs
         */
        $file = $this->addBelongsTo($request, $file);

        /*
         * Create many-to-many relationship if file belongs to a terrarium or animal
         */
        if (in_array($file->belongsTo_type, ['Terrarium', 'Animal'])) {
            $class = 'App\\' . $file->belongsTo_type;
            $object = $class::find($file->belongsTo_id);
            $object->files()->save($file);
        }

        if ($request->filled('use_as_background') && $request->input('use_as_background') == 'On') {
            if (is_null($file->property('generic', 'is_default_background'))) {
                $p = Property::create();
                $p->belongsTo_type = 'File';
                $p->belongsTo_id = $file->id;
                $p->name = 'is_default_background';
                $p->value = true;
                $p->save();
            }
        }
        else {
            foreach ($file->properties()->where('name', 'is_default_background')->get() as $p) {
                $p->delete();
            }
        }

        $file->state = 'Uploaded';
        $file->save();

        return $this->setStatusCode(200)->respondWithData(
            [
                'id'    =>  $file->id
            ],
            [
                'redirect' => [
                    'uri'   => url($file->url()),
                    'delay' => 100
                ]
            ]
        );

    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {

        if (Gate::denies('api-write:file')) {
            return $this->respondUnauthorized();
        }

        $file = File::find($id);
        if (is_null($file)) {
            return $this->respondNotFound('File not found');
        }

        $file->display_name = $request->input('display_name');

        /*
         * Look for optional inputs
         */
        if ($request->filled('belongsTo_type') && $request->filled('belongsTo_id')) {
            $class_name = 'App\\' . $request->input('belongsTo_type');
            if (class_exists($class_name)) {
                $belongs = $class_name::find($request->input('belongsTo_id'));
                if (is_null($belongs)) {
                    return $this->setStatusCode(422)
                                ->respondWithError('Model not found');
                }

                $file->belongsTo_type = $request->input('belongsTo_type');
                $file->belongsTo_id = $belongs->id;
            } else {
                return $this->setStatusCode(422)
                            ->respondWithError('Class not found');
            }
        }

        if ($request->filled('use_as_background')) {
            if (is_null($file->property('generic', 'is_default_background'))) {
                $p = Property::create();
                $p->belongsTo_type = 'File';
                $p->belongsTo_id = $file->id;
                $p->name = 'is_default_background';
                $p->value = true;
                $p->save();
            }
        }
        else {
            foreach ($file->properties()->where('name', 'is_default_background')->get() as $p) {
                $p->delete();
            }
        }

        $file->save();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('files'),
                'delay' => 1000
            ]
        ]);

    }

    /**
     * @param Request $request
     * @param $type
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function associate(Request $request, $type, $id)
    {
        $source_class = 'App\\' . $type;
        $source = $source_class::find($id);
        if (is_null($source)) {
            return $this->respondNotFound('Source not found');
        }

        $file = File::find($request->input('file'));
        if (is_null($file)) {
            return $this->respondNotFound('File not found');
        }

        $source->files()->save($file);

        return $this->respondWithData([]);
    }

    /**
     * @param Request $request
     * @param $type
     * @param $id
     * @param $file_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function associate_delete(Request $request, $type, $id, $file_id)
    {
        $source_class = 'App\\' . $type;
        $source = $source_class::find($id);
        if (is_null($source)) {
            return $this->respondNotFound('Source not found');
        }

        $file = File::find($file_id);
        if (is_null($file)) {
            return $this->respondNotFound('File not found');
        }

        $source->files()->detach($file);

        return $this->respondWithData([]);
    }

}
