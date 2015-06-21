# Laravel 5 Api Controller
A simple api controller utilizing league fractal, inspired by Jeffrey Way.

## Installation
```composer require eventhomes/laravel-apicontroller```

## Basic Usage
```php
...
use EventHomes\Api\ApiController;

class MyController extends Controller {

    use ApiController;
    
    public function __contstruct(Request $request)
    {
        $this->parseIncludes($request);
    }
}
```

## Customize Fractal

```php
...
use EventHomes\Api\ApiController;

class MyController extends Controller {

    use ApiController;
    
    public function __contstruct(Request $request, Manager $manager)
    {
        $manager->setSerializer(new ArraySerializer());
        $this->setFractal($manager);
        $this->parseIncludes($request);
    }
}
```