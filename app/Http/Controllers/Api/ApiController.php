<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

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
     * ApiController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth.basic');
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
     * @param null $errorCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithError($message, $entityId = null)
    {
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
        return response()->json($data)->setStatusCode($this->getStatusCode());
    }

    /**
     * @param $required_fields
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

    /*
     * .../?filter[field]=operator:comparison
     * .../?filter[field]=operator
     *
     * Example query:
     * .../?filter[name]=like:*s00hvm*&filter[status]=null:
     * will match all entities where name contains s00hvm and status is null
     *
     * Multiple filters on one field can be chained:
     * .../?filter[created_at]=lt:2016-12-10:and:gt:2016-12-08
     *
     * special operators:
     * like, notlike, today, nottoday
     */
    /**
     * @param Request $request
     * @param $query
     * @return mixed
     */
    protected function filter(Request $request, $query)
    {
        if ($request->has('filter')) {
            foreach ($request->input('filter') as $field => $value) {
                $fields = explode(":and:", $value);
                foreach ($fields as $v) {
                    $field_filter = explode(":", $v);
                    if (count($field_filter) > 1) {
                        $field_filter[1] = str_replace('*', '%', $field_filter[1]);
                        $operator = str_replace('notlike', 'not like', $field_filter[0]);
                        $operator = str_replace('gt', '>', $operator);
                        $operator = str_replace('lt', '<', $operator);
                        $operator = str_replace('eq', '=', $operator);
                        $query = $query->where($field, $operator, $field_filter[1]);
                    } else {
                        $field_filter = $value;

                        switch ($field_filter) {
                            case 'today':
                                $query = $query->where($field, 'like', Carbon::now()->format('Y-m-d') . '%');
                                break;
                            case 'nottoday':
                                $query = $query->where(function ($q) use ($field) {
                                    $q->where($field, 'not like', Carbon::now()->format('Y-m-d') . '%')
                                        ->orWhereNull($field);
                                });
                                break;
                            case 'null':
                                $query = $query->whereNull($field);
                                break;
                            case 'notnull':
                                $query = $query->whereNotNull($field);
                                break;
                            default:
                                $query = $query->where($field, $value);
                                break;
                        }
                    }
                }
            }
        }

        if ($request->has('limit')) {
            $query = $query->limit($request->input('limit'));
        }

        if ($request->has('order')) {
            foreach ($request->input('order') as $field=>$value) {
                $query = $query->orderBy($field, $value);
            }
        }

        return $query;
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
