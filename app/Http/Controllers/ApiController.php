<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class ApiController
 * @package App\Http\Controllers
 */
class ApiController extends Controller
{

    /**
     * @var
     */
    protected $statusCode;

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
    public function respondWithError($message, $entityId = null, $errorCode = null)
    {
        return $this->respond([
            'http_code' => $this->getStatusCode(),
            'error'     => [
                'error_code'    => $errorCode,
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
    public function respondWithData($data, $meta = [])
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

}
