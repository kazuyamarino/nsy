# NSY USER GUIDE
NSY is a simple PHP Framework that works well on MVC or HMVC mode, its made with HTML5 Boilerplate and Foundation CSS Framework. NSY also provides Font-Awesome and several optimizations for Datatables plugin.

Site example :
<a href="https://nsy.kazuyamarino.com/" target="_blank">https://nsy.kazuyamarino.com/</a>

## Configuration File
* NSY_Config.php
* Routing.php
* main.js

<hr>

### NSY_Config.php File
The NSY_Config class provides a means to retrieve configuration preferences. These preferences can come from the default config file `System/Core/NSY_Config.php` or you can custom it with your own setting.

By default NSY_Config file is required by `index.php` in the <strong>Public</strong> folder *(see line 3 & line 43 to 46 of the index.php file)*.

<hr>

### Routing.php file
NSY routing system using classes from [Macaw route by Noah Buscher](https://github.com/noahbuscher/macaw)

>Macaw is a simple, open source PHP router. It's super small (~150 LOC), fast, and has some great >annotated source code. This class allows you to just throw it into your project and start using it >immediately.

#### Examples

```PHP
NSY_Router::get('/', function() {
  echo 'Hello world!';
});

NSY_Router::dispatch();
```

NSY_Router also supports lambda URIs, such as:

```PHP
NSY_Router::get('/(:any)', function($slug) {
  echo 'The slug is: ' . $slug;
});

NSY_Router::dispatch();
```

You can also make requests for HTTP methods in NSY_Router, so you could also do:

```PHP
NSY_Router::get('/', function() {
  echo 'I'm a GET request!';
});

NSY_Router::post('/', function() {
  echo 'I'm a POST request!';
});

NSY_Router::any('/', function() {
  echo 'I can be both a GET and a POST request!';
});

NSY_Router::dispatch();
```

#### Example passing to a controller instead of a closure

It's possible to pass the namespace path to a controller instead of the closure:

For this demo lets say I have a folder called controllers with a demo.php

index.php:

```php
NSY_Router::get('/', 'Controllers\demo@index');
NSY_Router::get('page', 'Controllers\demo@page');
NSY_Router::get('view/(:num)', 'Controllers\demo@view');

NSY_Router::dispatch();
```

demo.php:

```php
<?php
namespace controllers;

class Demo {

    public function index()
    {
        echo 'home';
    }

    public function page()
    {
        echo 'page';
    }

    public function view($id)
    {
        echo $id;
    }

}
```

Lastly, if there is no route defined for a certain location, you can make NSY_Router run a custom callback, like:

```PHP
NSY_Router::error(function() {
  echo '404 :: Not Found';
});
```

If you don't specify an error callback, NSY_Router will just echo `404`.

<hr>

### main.js file

<hr>

## License

The code is available under the [MIT license](LICENSE.txt)..
