# NSY USER GUIDE
NSY is a simple PHP Framework that works well on MVC or HMVC mode.

Site example :
<a href="https://nsy.kazuyamarino.com/" target="_blank">https://nsy.kazuyamarino.com/</a>

### Composer
Composer helps you declare, manage, and install dependencies of PHP projects.

See [https://getcomposer.org/](https://getcomposer.org/) for more information and documentation.

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

Example make a `homepage` folder in the HMVC module folder and dump it with composer autoload :
* See line autoload on composer.json.
```
"autoload": {
	"psr-4": {
		"Core\\": "system/core/",
		"Models\\": "system/models/",
		"Controllers\\": "system/controllers/",
		"Modules\\Models\\": [
			"system/modules/homepage/models/"
		],
		"Modules\\Controllers\\": [
			"system/modules/homepage/controllers/"
		]
	},
```

* There is an example folder named in the module folder that was created named `homepage`, along with the namespaces.
```
"Modules\\Models\\": [
	"system/modules/homepage/models/"
],
"Modules\\Controllers\\": [
	"system/modules/homepage/controllers/"
]
```
The namespace is separated with `\\`, and then the path with `/`. There are 2 folders that will be autoloaded, first `system/modules/homepage/models/`, and second `system/modules/homepage/controllers/`.


* In the `homepage` folder there must be a `models` folder, `views` folder, and `controllers` folder.
```
modules
    │   └── homepage
    │       ├── controllers
    │       │  
    │       ├── models
    │       │  
    │       └── views
    │           
```
Should be like this. That it is!

* Now, you can generate autoload class in the `models` folder & `controllers` folder for the `homepage` with `composer dump-autoload -o` on the command line terminal.

<hr>

### Framework Configuration
The NSY_Framework Configuration is very simple. There are 3 config file in `system/config` directory :
* `app.php` for application setting such as system path of the framework.
* `database.php` for database connection setting.
* `site.php` for meta site configuration.
```
├── config
    │   ├── app.php
    │   ├── database.php
    │   └── site.php
```

By default NSY support `phpdotenv` library, that can read `.env` file *(see `.env` file on root directory)*.

* system.js file

>system.js is located in `public/js/config/system.js` folder.

In system.js there is a `base_url` configuration for javascript *(see line 1 to 20)*. This `base_url` is used for the purpose of initializing the function of the <strong>Datatable Ajax URL</strong> in the `public/js/datatables/init.js`*

(*) For Example see NSY Foundation repository [https://github.com/kazuyamarino/nsy-foundation](Here!)

<hr>

### Helpers
NSY_Helper is provided in the `system/helpers` folder, which is useful for creating custom methods that match what you want.

<hr>

### Routes
NSY Routing system using classes from [Macaw route by Noah Buscher](https://github.com/noahbuscher/macaw), and it's located in the `system/routes` directory.
```
├── routes
    │   ├── Api.php
    │   └── Web.php
```

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

You can also make requests for HTTP methods in NSY_Router, so you could also do:

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

## MVC & HMVC
* The Model View Controller (MVC) design pattern specifies that an application consist of a data model, presentation information, and control information. The pattern requires that each of these be separated into different objects.
* The Hierarchical Model View Controller (HMVC) is an evolution of the MVC pattern used for most web applications today. It came about as an answer to the scalability problems apparent within applications which used MVC.

<hr>

## Introducting to NSY Assets Manager
The easiest & best assets manager in history
made with love by Vikry Yuansah

How to use it? Simply follow this.
* First, you need to go to `system/libraries/`, there are 1 files, that is `Assets.php`.
* `NSY_AssetManager.php` is the core, it is located in `system/core` folder. `Assets.php` is the controller which regulates assets, if you want to manage the assets, please go to `Assets.php`.

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
pull::method_name();
```

For example :
```
pull::header_assets();
pull::footer_assets();
```


<hr>

## PSR-4 Autoloading
* NSY applies the concept of PSR-4 Autoloading. NSY has the `composer.json` file that can be dumped with [composer](https://getcomposer.org/download/) command `composer dump-autoload -o` when creating a folder structure that contains new class files.
* Complete information about PSR-4 can be read on the official [PHP-FIG](https://www.php-fig.org/psr/psr-4/) website.

<hr>

## NSY CLI (Command Line)
NSY CLI is a collection of commands to facilitate users in operating NSY. To start, open the terminal or git bash, then install it with:
* `chmod +x INSTALL.CLI.sh`
* ./INSTALL.CLI.sh
* Close the terminal or git bash, & open it again
* Then type command `hi_nsy` should display
```
Welcome to NSY CLI
NSY CLI installed successfully
```

For example of commands see below.
* Show list of HMVC Modules directory :
```
show_modules
```
* Show list of HMVC Controller files :
```
show_controllers hmvc <module-directory-name>
```
* Show list of HMVC Model files :
```
show_models hmvc <module-directory-name>
```
* Show list of MVC Controller files :
```
show_controllers mvc
```
* Show list of MVC Model files :
```
show_models mvc
```
* Show Welcome Message :
```
hi_nsy
```
* Dump mysql database :
```
mysql_dump_db <database-name> <username> <password>
```
* Dump mysql database (table only) :
```
mysql_dump_db <database-name> <username> <password> <table-name>
```
* Make HMVC Controller :
```
make_controller hmvc <module-directory-name> <controller-name>
```
* Make HMVC Model :
```
make_model hmvc <module-directory-name> <model-name>
```
* Make MVC Controller :
```
make_controller mvc <controller-name>
```
* Make MVC Model :
```
make_model mvc <model-name>
```

<hr>

## License
The code is available under the [MIT license](https://github.com/kazuyamarino/nsy/blob/master/LICENSE.txt)
