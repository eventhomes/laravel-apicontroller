<?php

namespace EventHomes\Api;

use EventHomes\Api\ApiController;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\Manager;
use League\Fractal\Serializer\ArraySerializer;

class FractalHelper {

    use ApiController;

    /**
     * @var Manager
     */
    protected $fractal;

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