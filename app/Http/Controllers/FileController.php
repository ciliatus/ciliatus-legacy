<?php

namespace App\Http\Controllers;

use App\File;
use App\Http\Transformers\FileTransformer;
use Auth;
use Carbon\Carbon;
use ErrorException;
use Gate;
use \Illuminate\Http\Request;
use Webpatser\Uuid\Uuid;


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
    public function index()
    {
        if (Gate::denies('api-list')) {
            return $this->respondUnauthorized();
        }

        $files = File::paginate(10);

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
            $this->fileTransformer->transform($file->toArray())
        );
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {

        if (Gate::denies('api-write:file')) {
            return $this->respondUnauthorized();
        }

        $file = File::find($request->input('id'));
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

        $required_inputs = ['name', 'file'];
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
        $file->display_name = $request->input('name');
        $file->parent_path = $parent_path;
        $file->name = $file->id . '.' .
            strtolower($request->file('file')->getClientOriginalExtension());

        $file->user_id = Auth::user()->id;
        $file->state = 'Creating';

        /*
         * Look for optional inputs
         */
        if ($request->has('belongsto_type') && $request->has('belongsto_id')) {
            $class_name = 'App\\' . $request->get('belongsto_type');
            if (class_exists($class_name)) {
                $belongs = $class_name::find($request->input('belongsto_id'));
                if (is_null($belongs)) {
                    return $this->setStatusCode(422)
                                ->respondWithError('Model not found');
                }

                $file->belongsTo_type = $request->get('belongsto_type');
                $file->belongsTo_id = $belongs->id;
            }
            else {
                return $this->setStatusCode(422)
                            ->respondWithError('Class not found');
            }
        }

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
        chmod($file->path(), 0664);

        $file->mimetype = mime_content_type($file->path());
        $file->state = 'Uploaded';
        $file->save();

        return $this->setStatusCode(200)->respondWithData(
            [
                'id'    =>  $file->id
            ],
            [
                'redirect' => [
                    'uri'   => url('files/' . $file->id . '/edit'),
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
        if ($request->has('belongsto_type') && $request->has('belongsto_id')) {
            $class_name = 'App\\' . $request->get('belongsto_type');
            if (class_exists($class_name)) {
                $belongs = $class_name::find($request->input('belongsto_id'));
                if (is_null($belongs)) {
                    return $this->setStatusCode(422)
                        ->respondWithError('Model not found');
                }

                $file->belongsTo_type = $request->get('belongsto_type');
                $file->belongsTo_id = $belongs->id;
            } else {
                return $this->setStatusCode(422)
                    ->respondWithError('Class not found');
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
