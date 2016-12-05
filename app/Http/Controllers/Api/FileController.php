<?php

namespace App\Http\Controllers\Api;

use App\File;
use App\Property;
use App\Http\Transformers\FileTransformer;
use Auth;
use Carbon\Carbon;
use ErrorException;
use Gate;
use \Illuminate\Http\Request;


/**
 * Class FileController
 * @package App\Http\Controllers
 */
class FileController extends ApiController
{
    /**
     * @var FileTransformer
     */
    protected $fileTransformer;

    /**
     * FileController constructor.
     * @param FileTransformer $_fileTransformer
     */
    public function __construct(FileTransformer $_fileTransformer)
    {
        parent::__construct();
        $this->fileTransformer = $_fileTransformer;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if (Gate::denies('api-list')) {
            return $this->respondUnauthorized();
        }

        $files = File::query();

        $files = $this->filter($request, $files);


        /*
         * If raw is passed, pagination will be ignored
         * Permission api-list:raw is required
         */
        if ($request->has('raw') && Gate::allows('api-list:raw')) {

            return $this->setStatusCode(200)->respondWithData(
                $this->fileTransformer->transformCollection(
                    $files->toArray()
                )
            );

        }

        $files = $files->paginate(env('PAGINATION_PER_PAGE', 20));

        foreach ($files->items() as &$f) {
            $f->path_internal = $f->path_internal();
            $f->path_external = $f->path_external();
        }

        return $this->setStatusCode(200)->respondWithPagination(
            $this->fileTransformer->transformCollection(
                $files->toArray()['data']
            ),
            $files
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

        $file = File::with('properties')->find($id);

        if (!$file) {
            return $this->respondNotFound('File not found');
        }

        return $this->setStatusCode(200)->respondWithData(
            $this->fileTransformer->transform(
                $file->toArray()
            )
        );
    }


    /**
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {

        if (Gate::denies('api-write:file')) {
            return $this->respondUnauthorized();
        }

        $required_inputs = ['file'];
        if (!$this->checkInput($required_inputs, $request)) {
            return $this->setStatusCode(422)
                        ->setErrorCode(101)
                        ->respondWithError('Required inputs: ' . implode(',', $required_inputs));
        }

        $year_str = Carbon::now()->year;
        $month_str = str_pad(Carbon::now()->month, 2, '0', STR_PAD_LEFT);
        $parent_path = 'storage/app/files';

        /*
         * check whether year/month folder exists
         * create if not
         */
        $parent_path = File::joinPath([
            $parent_path,
            $year_str,
            $month_str
        ]);
        $absolute_path = File::joinPath([
            base_path(),
            $parent_path
        ]);

        if (!is_dir($absolute_path)) {
            try {
                umask(0);
                mkdir($absolute_path, 0774, true);
            }
            catch (ErrorException $ex) {
                return $this->setStatusCode(500)
                            ->respondWithError('Directory could not be created.' . $ex->getMessage() . $parent_path);
            }
        }

        /*
         * Create file model
         */
        $file = File::create();
        $file->parent_path  = $parent_path;
        $file->user_id      = Auth::user()->id;
        $file->state        = 'Creating';
        $file->name         = $file->id . '.' .
            strtolower($request->file('file')->getClientOriginalExtension());

        /*
         * Look for optional inputs
         */
        $file = $this->addBelongsTo($request, $file);

        /*
         * Move file to storage
         */
        $request->file('file')->move(
            File::joinPath([
                base_path(),
                $file->parent_path
            ]),
            $file->name
        );
        umask(0);
        chmod($file->path_internal(), 0664);

        switch ($request->file('file')->getClientMimeType()) {
            case 'image/jpeg':
                $exif = exif_read_data($file->path_internal(), 0, true);
                if ($exif) {
                    foreach($exif as $key=>$section) {
                        foreach($section as $name=>$value) {
                            if (!is_array($value)) {
                                $fp = Property::create();
                                $fp->belongsTo_type = 'File';
                                $fp->belongsTo_id = $file->id;
                                $fp->type = 'exif';
                                $fp->name = $key.$name;
                                $fp->value = $value;
                                $fp->save();
                            }
                        }
                    }
                }
        }

        if ($request->has('use_as_background') && $request->input('use_as_background') == 'On') {
            if (is_null($file->property('is_default_background'))) {
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

        $file->display_name = $request->file('file')->getClientOriginalName();
        $file->mimetype = $request->file('file')->getClientMimeType();
        $file->size = $request->file('file')->getClientSize();
        $file->state = 'Uploaded';
        $file->save();

        return $this->setStatusCode(200)->respondWithData(
            [
                'id'    =>  $file->id
            ],
            [
                'redirect' => [
                    'uri'   => url('files/' . $file->id),
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

        if (Gate::denies('api-write:file')) {
            return $this->respondUnauthorized();
        }

        $file = File::find($request->input('id'));
        if (is_null($file)) {
            return $this->respondNotFound('File not found');
        }

        $file->display_name = $request->input('display_name');

        /*
         * Look for optional inputs
         */
        if ($request->has('belongsTo_type') && $request->has('belongsTo_id')) {
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

        if ($request->has('use_as_background')) {
            if (is_null($file->property('is_default_background'))) {
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

}
