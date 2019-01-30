# NSY USER GUIDE
NSY is a simple PHP Framework that works well on MVC or HMVC mode, its a core of NSY Framework.

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

#### Examples :

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

#### Example passing to a controller instead of a closure :

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

>main.js is located in `Public/Template/js/main.js` folder.

In main.js there is a `base_url` configuration for javascript *(see line 1 to 20)*. This `base_url` is used for the purpose of initializing the function of the <strong>Datatable Ajax URL</strong> in the `Public/Template/js/datatables/init.js`

<hr>

## MVC & HMVC
* The Model View Controller (MVC) design pattern specifies that an application consist of a data model, presentation information, and control information. The pattern requires that each of these be separated into different objects.
* The Hierarchical Model View Controller (HMVC) is an evolution of the MVC pattern used for most web applications today. It came about as an answer to the scalability problems apparent within applications which used MVC.

<hr>

## Introducting to NSY Assets Manager
The easiest & best assets manager in history
made with love by Vikry Yuansah

How to use it? Simply follow this.
* First, you need to go to `System/Libraries/AssetManager/`, there are 2 files, that is `Assets.php` & `NSY_AssetManager.php`.
* `NSY_AssetManager.php` is the core, & `Assets.php` is the controller which regulates assets, if you want to manage the assets, please go to `Assets.php`.

Create `<meta>` tag :
```
$this->meta('name', 'content');
```

Create `<link>` tag :
```
$this->link('filename/url_filename', 'attribute_rel', 'attribute_type');
```

Create `<script>` tag :
```
$this->script('filename/url_filename', 'attribute_type', 'attribute_charset', 'async defer');
```

You can write any html tags with custom method :
```
$this->custom('anythings');
```

<hr>

## PSR-4 Autoloading
* NSY applies the concept of PSR-4 Autoloading. NSY has the `composer.json` file that can be dumped with [composer](https://getcomposer.org/download/) command `composer dump-autoload` when creating a folder structure that contains new class files.
* Complete information about PSR-4 can be read on the official [PHP-FIG](https://www.php-fig.org/psr/psr-4/) website.

<hr>

## License

The code is available under the [MIT license](LICENSE.txt)..
