<?php

namespace App\Http\Controllers\Api;

use App\CiliatusModel;
use App\Http\Controllers\Controller;
use App\Property;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Matthenning\EloquentApiFilter\EloquentApiFilter;

/**
 * Class ApiController
 * @package App\Http\Controllers
 */
class ApiController extends Controller
{
    /**
     * @var
     */
    protected $statusCode = 200;

    /**
     * @var
     */
    protected $errorCode;

    /**
     * @var array
     */
    protected $debug_info = [];

    /**
     * ApiController constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @param mixed $errorCode
     * @return ApiController
     */
    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    /**
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondNotFound($message = 'Not found')
    {
        return $this->setStatusCode(404)->respondWithError($message);
    }

    /**
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondUnauthorized($message = 'Unauthorized')
    {
        return $this->setStatusCode(401)->respondWithError($message);
    }

    /**
     * @param $message
     * @param null $entityId
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithError($message, $entityId = null)
    {
        \Log::info('Request terminated in error ' . $this->getStatusCode() . ' (' . $this->getErrorCode() . '): ' . $message);

        return $this->respond([
            'http_code' => $this->getStatusCode(),
            'error'     => [
                'error_code'    => $this->getErrorCode(),
                'message'       => $message
            ]
        ]);
    }

    /**
     * @param $data
     * @param LengthAwarePaginator $paginator
     * @param array $meta
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithPagination($data, LengthAwarePaginator $paginator, $meta = [])
    {
        $meta['pagination'] = [
            'total_items'   => $paginator->total(),
            'total_pages'   => $paginator->lastPage(),
            'current_page'  => $paginator->currentPage(),
            'per_page'         => $paginator->perPage()
        ];
        return $this->respondWithData($data, $meta);
    }


    /**
     * @param $data
     * @param array $meta
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithData($data = [], $meta = [])
    {
        return $this->respond([
            'http_code' => $this->getStatusCode(),
            'data'  =>  $data,
            'meta'  =>  $meta
        ]);
    }

    /**
     * @param $data
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function respond($data, $headers = [])
    {
        if (count($this->debug_info) > 0) {
            $data['debug'] = $this->debug_info;
        }

        return response()->json($data)->setStatusCode($this->getStatusCode());
    }

    /**
     * Append debug info to the response
     *
     * @param $data
     */
    public function appendDebugInfo($data)
    {
        $this->debug_info[] = $data;
    }

    /**
     * @param $required_fields
     * @param Request $request
     * @return bool
     */
    public function checkInput($required_fields, Request $request)
    {
        foreach ($required_fields as $f) {
            if (!$request->has($f) && !$request->hasFile($f)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Retrieve belonging object from belongsTo request field
     * and set properties on $object
     *
     * @param $request
     * @param $object
     * @param bool $dynamic
     * @return \Illuminate\Http\JsonResponse
     */
    protected function addBelongsTo($request, $object, $dynamic = true)
    {
        if ($request->has('belongsTo') && $request->input('belongsTo') != '') {
            $belongsTo_type = explode("|", $request->input('belongsTo'))[0];
            $belongsTo_id = explode("|", $request->input('belongsTo'))[1];
            $class_name = 'App\\' . $belongsTo_type;
            if (class_exists($class_name)) {
                $belongs = $class_name::find($belongsTo_id);
                if (is_null($belongs)) {
                    return $this->setStatusCode(422)
                        ->respondWithError('Model not found');
                }

                if ($dynamic) {
                    $object->belongsTo_type = ucfirst($belongsTo_type);
                    $object->belongsTo_id = $belongs->id;
                }
                else {
                    $field_name = $this->fromCamelCase($belongsTo_type) . '_id';
                    $object->$field_name = $belongs->id;
                }
            }
            else {
                return $this->setStatusCode(422)
                    ->respondWithError('Class not found');
            }
        }

        return $object;
    }

    /**
     * Return belonging object name and id from belongsTo request field
     *
     * @param $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    protected function getBelongsTo($request)
    {
        if ($request->has('belongsTo') && $request->input('belongsTo') != '') {
            $belongsTo_type = explode("|", $request->input('belongsTo'))[0];
            $belongsTo_id = explode("|", $request->input('belongsTo'))[1];
            $class_name = 'App\\' . $belongsTo_type;
            if (class_exists($class_name)) {
                $belongs = $class_name::find($belongsTo_id);
                if (is_null($belongs)) {
                    return $this->setStatusCode(422)
                        ->respondWithError('Model not found');
                }
            }
            else {
                return $this->setStatusCode(422)
                    ->respondWithError('Class not found');
            }

            return [
                'belongsTo_type' => $belongsTo_type,
                'belongsTo_id' => $belongsTo_id
            ];
        }

        return null;
    }

    /**
     * @param Request $request
     * @param $query
     * @return Builder
     */
    protected function filter(Request $request, Builder $query)
    {
        return (new EloquentApiFilter($request, $query))->filter();
    }

    /**
     * Converts selected fields from data array
     * to the desired format (e.g. csv)
     *
     * @param $type
     * @param $fields
     * @param $data
     * @return null|string
     */
    public function convert($type, $fields, $data)
    {
        switch ($type) {

            case 'csv':
                $fields_final = [];
                foreach ($fields as $f=>$display_name) {
                    if (is_null($display_name)) {
                        $fields_final[] = $f;
                    }
                    else {
                        $fields_final[] = $display_name;
                    }
                }

                $csv = implode(',', $fields_final);

                foreach ($data as $row) {
                    $row_arr = [];
                    foreach ($fields as $f=>$display_name) {
                        if (isset($row[$f])) {
                            $row_arr[] = $row[$f];
                        }
                    }
                    $csv .= PHP_EOL . implode(',', $row_arr);
                }

                return $csv;
        }

        return null;
    }

    /**
     * Updates properties of an object.
     *
     * $fields has to contain an array of fields to update.
     * You can use an associative array to pull data from
     * request inputs with different names than the model
     * property.
     *
     * Example array structure for $fields:
     *
     *  [
     *      'name',
     *      'value' => 'form_input_value'
     *  ]
     *
     * @param CiliatusModel $model
     * @param Request $request
     * @param array $fields
     * @return CiliatusModel
     */
    public function updateModelProperties(CiliatusModel $model, Request $request, array $fields)
    {
        foreach ($fields as $model_field=>$request_field)
        {
            if (is_int($model_field)) { // if true, this is no associative array -> model field equals request field
                $model_field = $request_field;
            }
            if ($request->exists($request_field)) {
                if ($request->has($request_field)) {
                    $model->$model_field = $request->get($request_field);
                }
                else {
                    $model->$model_field = null;
                }
            }
        }

        return $model;
    }

    /**
     * Updates external Properties of a model.
     * If a Property object doesn't exist it will be created.
     * Existing properties not contained in $fields will NOT
     * be removed by default. Use $remove_not_existing
     *
     * Example array structure for $fields:
     *  [
     *      'SomePropertyType' => [
     *          'some_property' // value will be retrieved from $request->input('SomePropertyType::some_property')
     *          'another_property'  =>  'more stuff'
     *      ]
     *  ];
     *
     * @param CiliatusModel $model
     * @param Request $request
     * @param array $fields
     * @param boolean $remove_not_existing If true: Existing properties not contained in the request will be removed
     * @return CiliatusModel
     */
    public function updateExternalProperties(CiliatusModel $model, Request $request, array $fields, $remove_not_existing = false)
    {
        foreach ($fields as $property_type=>$properties) {
            foreach ($properties as $name=>$value) {
                if (is_int($name)) { // if true, this is no associative array -> auto detect value from request
                    $name = $value;
                    $value = $request->exists($property_type . '::' . $name) ? $request->get($property_type . '::' . $name) : null;
                }

                $property = $model->properties()->where('type', $property_type)->where('name', $name)->get()->first();

                if (!is_null($property) && is_null($value) && $remove_not_existing) {
                    $property->delete();
                }
                elseif (!is_null($value)) {
                    $this->updateExternalProperty($model, $property_type, $name, $value, $property);
                }
            }
        }

        return $model;
    }

    /**
     * @param CiliatusModel $model
     * @param Property|null $property
     * @param $type
     * @param $name
     * @param $value
     * @return mixed
     */
    protected function updateExternalProperty(CiliatusModel $model, $type, $name, $value, $property = null)
    {
        if (is_null($property)) {
            $class_name = explode('\\', get_class($model));
            $class_name = end($class_name);
            Property::create([
                'belongsTo_type'    => $class_name,
                'belongsTo_id'      => $model->id,
                'type'              => $type,
                'name'              => $name,
                'value'             => $value
            ]);
        }
        else {
            $property->value = $value;
            $property->save();
        }

        return $property;
    }

    /**
     * @TODO: move to helper class
     *
     * @param $uuid
     * @return int
     */
    public function checkUuid($uuid)
    {
        return preg_match('/^\{?[A-Za-z0-9]{8}-[A-Za-z0-9]{4}-[A-Za-z0-9]{4}-[A-Za-z0-9]{4}-[A-Za-z0-9]{12}\}?$/', $uuid);
    }

    /**
     * @param $string
     * @return string
     */
    public function fromCamelCase($string)
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
    }

}
