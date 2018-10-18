# NSY USER GUIDE
NSY is a simple PHP Framework that works well on MVC or HMVC mode, its made with HTML5 Boilerplate and Foundation CSS Framework. NSY also provides Font-Awesome and several optimizations for Datatables plugin.

Site example :
<a href="https://nsy.kazuyamarino.com/" target="_blank">https://nsy.kazuyamarino.com/</a>


## User Guide.

### Configuration File
	- NSY_Config.PHP
	- Routing.PHP
	- main.js

#### NSY_Config.php File
The NSY_Config class provides a means to retrieve configuration preferences. These preferences can come from the default config file (System/Core/NSY_Config.php) or you can custom it with your own setting.

By default NSY_Config file is required by index.php in the Public folder (see line 3 & line 43 to 46 of the index.php file).

#### Routing.php file
NSY routing system using classes from Macaw route by Noah Buscher

Macaw is a simple, open source PHP router. It's super small (~150 LOC), fast, and has some great annotated source code. This class allows you to just throw it into your project and start using it immediately.

##### Examples

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

Lastly, if there is no route defined for a certain location, you can make Macaw run a custom callback, like:

```PHP
Macaw::error(function() {
  echo '404 :: Not Found';
});
```

If you don't specify an error callback, Macaw will just echo `404`.


## License

The code is available under the [MIT license](LICENSE.txt)..
