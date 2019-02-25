# NSY USER GUIDE
NSY is a simple PHP Framework that works well on MVC or HMVC mode, its a core of NSY Framework, with no more Stylesheet and Javascript.

Site example :
<a href="https://nsy.kazuyamarino.com/" target="_blank">https://nsy.kazuyamarino.com/</a>

## Configuration File
* composer.json
* NSY_Config.php
* Routing.php
* main.js

<hr>

### composer.json
Composer helps you declare, manage, and install dependencies of PHP projects.

See [https://getcomposer.org/](https://getcomposer.org/) for more information and documentation.

[![Build Status](https://travis-ci.org/composer/composer.svg?branch=master)](https://travis-ci.org/composer/composer)

Installation / Usage
--------------------

Download and install Composer by following the [official instructions](https://getcomposer.org/download/).

For usage, see [the documentation](https://getcomposer.org/doc/).

Packages
--------

Find packages on [Packagist](https://packagist.org).

Composer on NSY framework
-------------------------

The composer on the nsy framework has a function to generate autoload in the HMVC module folder.

NSY applies the concept of PSR-4 Autoloading. NSY has the `composer.json` file that can be dumped with [composer](https://getcomposer.org/download/) command `composer dump-autoload -o` when creating a folder structure that contains new class files.

Example make a `Homepage` folder in the HMVC module folder and dump it with composer autoload :
* See line autoload on composer.json.
```
"autoload": {
	"psr-4": {
		"Core\\": "System/Core/",
		"Models\\": "System/Models/",
		"Controllers\\": "System/Controllers/",
		"Modules\\Models\\": [
			"System/Modules/Homepage/Models/"
		],
		"Modules\\Controllers\\": [
			"System/Modules/Homepage/Controllers/"
		]
	},
```

* There is an example folder named in the module folder that was created named `Homepage`, along with the namespaces.
```
"Modules\\Models\\": [
	"System/Modules/Homepage/Models/"
],
"Modules\\Controllers\\": [
	"System/Modules/Homepage/Controllers/"
]
```
The namespace is separated with `\\`, and then the path with `/`. There are 2 folders that will be autoloaded, first `System/Modules/Homepage/Models/`, and second `System/Modules/Homepage/Controllers/`.


* In the `Homepage` folder there must be a `Models` folder, `Views` folder, and `Controllers` folder.
```
├── Modules
    │   └── Homepage
    │       ├── Controllers
    │       │  
    │       ├── Models
    │       │  
    │       └── Views
    │           
```
Should be like this. That it is!

* Now, you can generate autoload class in the `Models` folder & `Controllers` folder for the `Homepage` with `composer dump-autoload -o` on the command line terminal.

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
route::get('/', function() {
  echo 'Hello world!';
});

route::dispatch();
```

route also supports lambda URIs, such as:

```PHP
route::get('/(:any)', function($slug) {
  echo 'The slug is: ' . $slug;
});

route::dispatch();
```

You can also make requests for HTTP methods in route, so you could also do:

```PHP
route::get('/', function() {
  echo 'I'm a GET request!';
});

route::post('/', function() {
  echo 'I'm a POST request!';
});

route::any('/', function() {
  echo 'I can be both a GET and a POST request!';
});
```

#### Example passing to a controller instead of a closure :

It's possible to pass the namespace path to a controller instead of the closure:

For this demo lets say I have a folder called controllers with a demo.php

index.php:

```php
route::get('/', 'Controllers\demo@index');
route::get('page', 'Controllers\demo@page');
route::get('view/(:num)', 'Controllers\demo@view');
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
route::error(function() {
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
* First, you need to go to `System/Libraries/`, there are 1 files, that is `Assets.php`.
* `NSY_AssetManager.php` is the core, it is located in 'System/Core' folder. `Assets.php` is the controller which regulates assets, if you want to manage the assets, please go to `Assets.php`.

Create `<meta>` tag :
```
add::meta('name', 'content');
```

Create `<link>` tag :
```
add::link('filename/url_filename', 'attribute_rel', 'attribute_type');
```

Create `<script>` tag :
```
add::script('filename/url_filename', 'attribute_type', 'attribute_charset', 'async defer');
```

You can write any html tags with custom method :
```
add::custom('anythings');
```

* After that, to use it in View, you only need to call the static method name that you created like this.
```
get::method_name();
```

For example :
```
get::header_assets();
get::footer_assets();
```


<hr>

## PSR-4 Autoloading
* NSY applies the concept of PSR-4 Autoloading. NSY has the `composer.json` file that can be dumped with [composer](https://getcomposer.org/download/) command `composer dump-autoload -o` when creating a folder structure that contains new class files.
* Complete information about PSR-4 can be read on the official [PHP-FIG](https://www.php-fig.org/psr/psr-4/) website.

<hr>

## License
The code is available under the [MIT license](LICENSE.txt)..
