<?php

namespace App\Http\Controllers\Api;

use App\CiliatusModel;
use App\File;
use App\Property;
use App\System;
use Auth;
use Gate;
use Illuminate\Http\Request;


/**
 * Class FileController
 * @package App\Http\Controllers
 */
class FileController extends ApiController
{

    /**
     * FileController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->errorCodeNamespace = '20';
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
     * @throws \Exception
     */
    public function destroy(Request $request, $id)
    {

        if (Gate::denies('api-write:file')) {
            return $this->respondUnauthorized();
        }

        /**
         * @var File $file
         */
        $file = File::find($id);
        if (is_null($file)) {
            return $this->respondNotFound();
        }

        $file->delete();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('files')
            ]
        ]);

    }

    /**
     * Custom Error Codes
     *  - 201: No file to upload (request field empty)
     *  - 202: File size too large
     *
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
            return $this->setStatusCode(422)
                        ->setErrorCode('201')
                        ->respondWithErrorDefaultMessage();
        }

        if ($request->file('file')->getClientSize() > System::maxUploadFileSize()) {
            return $this->setStatusCode(422)
                        ->setErrorCode('202')
                        ->respondWithErrorDefaultMessage(['max_size' => System::maxUploadFileSize()/1024/1024]);
        }
        /**
         * Create file model
         * @var File $file
         */
        $file = File::createFromRequest($request, Auth::user()->id);

        /**
         * Look for optional inputs
         * @var File $file
         */
        $file = $this->addBelongsTo($request, $file);

        /**
         * Create many-to-many relationship if file belongs to a terrarium or animal
         */
        $class = 'App\\' . $file->belongsTo_type;

        /**
         * @var CiliatusModel $object
         */
        $object = $class::find($file->belongsTo_id);
        $object->files()->save($file);

        if ($request->filled('use_as_background') && $request->input('use_as_background') == 'On') {
            if (is_null($file->property('generic', 'is_default_background'))) {
                /**
                 * @var Property $p
                 */
                $p = new Property();
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
                    'uri'   => url($file->url())
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

        /**
         * @var File $file
         */
        $file = File::find($id);
        if (is_null($file)) {
            return $this->respondNotFound();
        }

        if ($request->filled('display_name')) {
            $file->display_name = $request->input('display_name');
        }

        if ($request->filled('name')) {
            $file->name = $request->input('name');
        }

        $file->save();

        return $this->setStatusCode(200)->respondWithData([], [
            'redirect' => [
                'uri'   => url('files')
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
            return $this->respondNotFound();
        }

        /**
         * @var File $file
         */
        $file = File::find($request->input('file'));
        if (is_null($file)) {
            return $this->respondNotFound();
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
            return $this->respondNotFound();
        }

        /**
         * @var File $file
         */
        $file = File::find($file_id);
        if (is_null($file)) {
            return $this->respondNotFound();
        }

        $source->files()->detach($file);

        return $this->respondWithData([]);
    }

    /**
     * @param Request $request
     * @param $type
     * @param $id
     * @param $file_id
     * @return mixed
     */
    public function set_background(Request $request, $type, $id, $file_id)
    {
        $source_class = 'App\\' . $type;
        $source = $source_class::find($id);
        if (is_null($source)) {
            return $this->respondNotFound();
        }

        /**
         * @var File $file
         */
        $file = File::find($file_id);
        if (is_null($file)) {
            return $this->respondNotFound();
        }

        if (!is_null($source->property('generic', 'background_file_id'))) {
            $source->property('generic', 'background_file_id')->delete();
        }

        /**
         * @var Property $p
         */
        $p = new Property();
        $p->belongsTo_type = $type;
        $p->belongsTo_id = $source->id;
        $p->name = 'background_file_id';
        $p->value = $file->id;
        $p->save();

        return $this->respondWithData(
            [],
            [
                'redirect' => [
                    'uri' => back()->getTargetUrl()
                ]
            ]
        );
    }
}
