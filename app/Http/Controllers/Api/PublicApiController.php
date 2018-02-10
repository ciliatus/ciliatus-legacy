<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class PublicApiController
 * @package App\Http\Controllers
 */
class PublicApiController extends \App\Http\Controllers\Controller
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
                'message'       => $message,
                'entity_id'     => $entityId
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
            if (!$request->filled($f) && !$request->hasFile($f)) {
                return false;
            }
        }

        return true;
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

}
