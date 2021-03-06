# Laravel 5 / Lumen 5 Api Controller
A simple api controller helper trait, compatible with Lumen 5+ and Laravel 5+. The goal of this project is to make creating API projects simple. Inspired by Jeffrey Way (https://www.laracasts.com)

## Installation
```composer require eventhomes/laravel-apicontroller```

## Basic Setup/Usage
```php
...
use EventHomes\Api\ApiController;

class MyController extends Controller {

    use ApiController;
    
    public function index() {
        return $this->respond(['status' => 'hello world']);
    }
    
}
```

## Api Helper functions
Please browse through the source to see a full list.
```php
//200 response
$this->respond();

//201 response
$this->respondCreated();

//500 error
$this->respondServerError();

//422 error
$this->respondUnprocessable();

//General error
$this->respondWithError('message here');
```

## Add Fractal Helpers

Please use [https://github.com/eventhomes/laravel-fractalhelper]