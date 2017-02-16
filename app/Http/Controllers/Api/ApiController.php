<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
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
     * Filter by related models' fields by using the dot-notaion:
     * .../?filter[physical_sensor.logical_sensor.name]=Sensor1
     *
     * special operators:
     * like, notlike, today, nottoday, null, notnull
     */
    /**
     * @param Request $request
     * @param $query
     * @return Builder
     */
    protected function filter(Request $request, Builder $query)
    {
        if ($request->has('filter')) {
            foreach ($request->input('filter') as $field=>$value) {
                $query = $this->applyFieldFilter($query, $field, $value);
            }
        }

        if ($request->has('order')) {
            foreach ($request->input('order') as $field=>$value) {
                $query = $this->applyOrder($query, $field, $value);
            }
        }

        if ($request->has('limit')) {
            $query = $query->limit($request->input('limit'));
        }

        return $query;
    }

    /**
     * Resolves :and: links and applies each filter
     *
     * @param Builder $query
     * @param $field
     * @param $value
     * @return Builder
     */
    private function applyFieldFilter(Builder $query, $field, $value)
    {
        $filters = explode(':and:', $value);
        foreach ($filters as $filter) {
            $query = $this->applyFilter($query, $field, $filter);
        }

        return $query;
    }

    /**
     * Applies a single filter
     *
     * @param Builder $query
     * @param $field
     * @param $filter
     * @return Builder
     */
    private function applyFilter(Builder $query, $field, $filter)
    {
        $filter = explode(':', $filter);
        if (count($filter) > 1) {
            $operator = $this->getFilterOperator($filter[0]);
            $value = $this->replaceWildcards($filter[1]);
        }
        else {
            $operator = '=';
            $value = $this->replaceWildcards($filter[0]);
        }

        $fields = explode('.', $field);
        if (count($fields) > 1) {
            return $this->applyNestedFilter($query, $fields, $operator, $value);
        }
        else {
            return $this->applyWhereClause($query, $field, $operator, $value);
        }
    }

    /**
     * Applies a nested filter.
     * Meaning a filter on a related field
     *
     * @param Builder $query
     * @param array $fields
     * @param $operator
     * @param $value
     * @return Builder
     */
    private function applyNestedFilter(Builder $query, array $fields, $operator, $value)
    {
        $relation_name = implode('.', array_slice($fields, 0, count($fields) - 1));
        $relation_field = $fields[count($fields) - 1];

        $that = $this;

        return $query->whereHas($relation_name, function ($query) use ($relation_field, $operator, $value, $that) {
            $query = $that->applyWhereClause($query, $relation_field, $operator, $value);
        });
    }

    /**
     * Applies a where clause.
     * Is used by applyFilter and applyNestedFilter
     * to apply the clause to the query.
     *
     * @param Builder $query
     * @param $field
     * @param $operator
     * @param $value
     * @return Builder
     */
    private function applyWhereClause(Builder $query, $field, $operator, $value) {
        switch ($value) {
            case 'today':
                return $query->where($field, 'like', Carbon::now()->format('Y-m-d') . '%');
            case 'nottoday':
                return $query->where(function ($q) use ($field) {
                    $q->where($field, 'not like', Carbon::now()->format('Y-m-d') . '%')
                        ->orWhereNull($field);
                });
            case 'null':
                return $query->whereNull($field);
            case 'notnull':
                return $query->whereNotNull($field);
            default:
                return $query->where($field, $operator, $value);
        }
    }

    /**
     * @param Builder $query
     * @param $field
     * @param $value
     * @return mixed
     */
    private function applyOrder(Builder $query, $field, $value)
    {
        $fields = explode('.', $field);
        if (count($fields) > 1) {
            return $this->applyNestedOrder($fields[0], $query, $fields[1], $value);
        }
        else {
            return $this->applyOrderByClause($query, $field, $value);
        }
    }

    /**
     * @TODO: This does not work yet. Order by doesn't seem to support this the same way whereHas does
     *
     * @param $relation_name
     * @param Builder $query
     * @param $relation_field
     * @param $value
     * @return mixed
     */
    private function applyNestedOrder($relation_name, Builder $query, $relation_field, $value)
    {
        $that = $this;
        return $query->orderBy($relation_name, function ($query) use ($relation_field, $value, $that) {
            $query = $that->applyOrderByClause($query, $relation_field, $value);
        });
    }

    /**
     * @param Builder $query
     * @param $field
     * @param $value
     * @return mixed
     */
    private function applyOrderByClause(Builder $query, $field, $value)
    {
        return $query->orderBy($field, $value);
    }

    /**
     * Replaces * wildcards with %
     * for usage in SQL
     *
     * @param $value
     * @return mixed
     */
    private function replaceWildcards($value)
    {
        return str_replace('*', '%', $value);
    }

    /**
     * Replaces operators from request string
     * for usage in SQL
     *
     * @param $filter
     * @return mixed
     */
    private function getFilterOperator($filter)
    {
        $operator = str_replace('notlike', 'not like', $filter);
        $operator = str_replace('gt', '>', $operator);
        $operator = str_replace('ge', '>=', $operator);
        $operator = str_replace('lt', '<', $operator);
        $operator = str_replace('le', '<=', $operator);
        $operator = str_replace('eq', '=', $operator);

        return $operator;
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
