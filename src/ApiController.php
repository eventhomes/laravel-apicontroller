<?php

namespace EventHomes\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Manager;
use League\Fractal\Serializer\ArraySerializer;

trait ApiController {

    /**
     * @var int
     */
    protected $statusCode = Response::HTTP_OK;

    /**
     * @var Manager
     */
    protected $fractal;

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param $statusCode
     *
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @return Manager $fractal
     */
    public function getFractal()
    {
        if ( !property_exists($this, 'fractal'))
        {
            $this->fractal = new Manager;
            $this->fractal->setSerializer(new ArraySerializer());
        }

        return $this->fractal;
    }

    /**
     * @param Manager $fractal
     *
     * @return $this
     */
    public function setFractal(Manager $fractal)
    {
        $this->fractal = $fractal;

        return $this;
    }

    /**
     * @param Request $request
     *
     * @return $this
     */
    public function parseIncludes(Request $request)
    {
        if ($request->has('include'))
        {
            $this->getFractal()->parseIncludes($request->get('include'));
        }

        return $this;
    }

    /**
     * @param string $message
     *
     * @return mixed
     */
    public function respondNotFound($message = 'Not Found')
    {
        return $this->setStatusCode(Response::HTTP_NOT_FOUND)->respondWithError($message);
    }

    /**
     * @param string $message
     *
     * @return mixed
     */
    public function respondBadRequest($message = 'Bad Request')
    {
        return $this->setStatusCode(Response::HTTP_BAD_REQUEST)->respondWithError($message);
    }

    /**
     * @param string $message
     *
     * @return mixed
     */
    public function respondServerError($message = 'Server Error')
    {
        return $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)->respondWithError($message);
    }

    /**
     * @param string $message
     *
     * @return mixed
     */
    public function respondConflict($message = 'Conflict')
    {
        return $this->setStatusCode(Response::HTTP_CONFLICT)->respondWithError($message);
    }

    /**
     * @param string $message
     *
     * @return mixed
     */
    public function respondUnprocessable($message = 'Unprocessable Entity')
    {
        return $this->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)->respondWithError($message);
    }

    /**
     * @param array $data
     *
     * @return mixed
     */
    public function respondCreated($data = [])
    {
        return $this->setStatusCode(Response::HTTP_CREATED)->respond($data);
    }

    /**
     * @param $data
     * @param array $headers
     *
     * @return mixed
     */
    public function respond($data, $headers = [])
    {
        return response()->json($data, $this->getStatusCode(), $headers);
    }

    /**
     * @param $message
     *
     * @return mixed
     */
    public function respondWithError($message)
    {
        return $this->respond([
            'error' => [
                'data'        => $message,
                'status_code' => $this->getStatusCode()
            ]
        ]);
    }

    /**
     * @param $item
     * @param $callback
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithItem($item, $callback)
    {
        $resource = new Item($item, $callback);

        $rootScope = $this->getFractal()->createData($resource);

        return $this->respond($rootScope->toArray());
    }

    /**
     * @param $collection
     * @param $callback
     *
     * @param null $paginator
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithCollection($collection, $callback, $paginator = null)
    {
        $resource = new Collection($collection, $callback);

        if ($paginator)
        {
            $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        }

        $rootScope = $this->getFractal()->createData($resource);

        return $this->respond($rootScope->toArray());
    }
}