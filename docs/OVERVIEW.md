# Overview

---

## Composer

Composer helps you declare, manage, and install dependencies of PHP projects.

See [https://getcomposer.org/](https://getcomposer.org/) for more information and documentation.

### Installation / Usage

Download and install Composer by following the [official instructions](https://getcomposer.org/download/).

For usage, see [the documentation](https://getcomposer.org/doc/).

#### Composer Packages

Find packages on [Packagist](https://packagist.org).

#### Composer on NSY framework
>
> The composer on the nsy framework has a function to generate autoload in the HMVC module folder.
>
>NSY applies the concept of PSR-4 Autoloading. NSY has the `composer.json` file that can be dumped with [composer](https://getcomposer.org/download/) command `composer dump-autoload -o` or [NSY CLI](https://github.com/kazuyamarino/nsy-docs/blob/master/USERGUIDE.md#nsy-cli-command-line) command `nsy dump:autoload` when creating a folder structure that contains new class files.
>

**For example :**
>
> * There is an example folder in the module folder that was created named `HMVC`, along with the namespaces.
>
> * In the `HMVC` folder there must be a `Models` folder, `Views` folder, and `Controllers` folder.
>
> The folder structure should be like this
>
>```text
>Modules
>    │   └── HMVC
>    │       ├── Controllers
>    │       │  
>    │       ├── Models
>    │       │  
>    │       └── Views
>    │           
>```
>
> * Now, you can generate autoload class in the `Models` folder & `Controllers` folder for the `HMVC` with `composer dump-autoload -o` or [NSY CLI](https://github.com/kazuyamarino/nsy-docs/blob/master/USERGUIDE.md#nsy-cli-command-line) command `nsy dump:autoload` on the command line terminal.

---

## Framework Configuration

The NSY Framework Configuration is very simple. There are 4 config files in `System/Config` + `env.php` in root (12-factor):

* `App.php` — application setting (env-aware `config_env() ?? fallback`: `APP_ENV`, `CSRF_TOKEN`, `timezone`, `locale`, `public_dir`, `css/js/img_dir`, `aliases`). `declare(strict_types=1)`.
* `Site.php` — site meta (env-aware: `SITE_TITLE`, `SITE_AUTHOR`, `SITE_KEYWORDS`, `SITE_DESC`, `SITE_EMAIL`, `APP_VERSION`, `APP_CODENAME`).
* `Assets.php` — minimal `header_assets()` / `footer_assets()` (1 line per asset `Add::link('main.css'...)`), cache-busting `?v=filemtime` handled transparently in `System/Core/NSY_Helpers_Global.php:css_url()`.
* `Mimes.php` — 182 mime types (modern: `webp`, `avif`, `woff2`, `wasm`, `webmanifest`), `declare(strict_types=1)`.

```text
├── Config
    │   ├── App.php      # env-aware
    │   ├── Site.php     # env-aware
    │   ├── Assets.php   # minimal header/footer
    │   └── Mimes.php    # 182 types
├── env.php              # copy from docs/env.example/env.example.php
```

* `system.js` file.

```text
system.js is located in `public/js/config/system.js` folder.
```

In system.js there is a `base_url` configuration for javascript *(see line 13 - 15)*. This `base_url` is used for the purpose of initializing the function of the **Datatable Ajax URL** in the `public/js/datatables/init.js`.

For Example see Shyffon repository, [See Example](https://github.com/kazuyamarino/shyffon/blob/master/public/assets/js/config/system.js).

---

## Helpers

The `System/Helpers` folder is useful for creating custom methods that match what you want and need.

If you want to make your own helper, then just make the desired method in the php file then save it in`System/Helpers`. And don't forget to autoload it on `composer.json` in the `files` parameters.

```php
"autoload": {
  "psr-4": {
    "System\\": "System/"
  },
  "files": [
    "System/Helpers/Custom_Method.php" // your custom helpers file
  ]
}
```

---

## Routes

NSY Routing system using classes from [Macaw route by Noah Buscher](https://github.com/noahbuscher/macaw), and it's located in the `System/Routes` directory.

```text
├── Routes
    │   └── General.php
    │   └── Modules.php
```

**Examples :**
>
> ```php
> Route::get('/', function() {
>   echo 'Hello world!';
> });
> ```

Route also supports regex parameters (Uri params), such as :

```php
':all'   => '.*',
':any'   => '[^/]+',
':slug'   => '[a-z0-9-]+',
':uslug'  => '[\w-]+',   // slug + underscores
':num'   => '[0-9]+',
':alpha'  => '[A-Za-z]+',
':alnum'  => '[0-9A-Za-z]+',
':date'    => '[0-9]{4}-[0-9]{2}-[0-9]{2}'
```

```PHP
Route::get('/(:any)', function($slug) {
  echo 'The slug is: ' . $slug;
});

// equivalent to Route::match('get|post|put|patch|delete|head|options',..)
Route::any('/', function() {};)
```

You can also make requests for HTTP methods in NSY_Router, so you could also do :

```PHP
Route::get('/', function() {
  echo "I'm a GET request!";
});

Route::post('/', function() {
  echo "I'm a POST request!";
});

Route::any('/', function() {
  echo 'I can be both a GET and a POST request!';
});
```

### Example passing to a controller instead of a closure

It's possible to pass the namespace path to a controller instead of the closure.

For this demo lets say I have a folder called controllers with a demo.php.

```php
// Demo.php :

<?php
namespace System\Controllers;

class Demo {

    public function __contruct()
    {

    }

    public function index()
    {
        echo 'home';
    }

    public function page()
    {
        echo 'page';
    }

    public function variable($id)
    {
        echo $id;
    }

}
```

```php
// General.php :

Route::get('/', function() {
  Route::goto([System\Controllers\Demo::class, 'index']);
});

Route::get('/page', function() {
  Route::goto([System\Controllers\Demo::class, 'page']);
});

Route::get('/variable/(:num)', function($id) {
 Route::goto([System\Controllers\Demo::class, 'variable'], $id);
});
```

### Example passing to a controller inside hmvc module

For this demo lets say I have a module folder called `HMVC` and folder controllers inside with a login.php name.

```php
// login.php :

namespace System\Modules\HMVC\Controllers;

class Login {

    public function index()
    {
        echo 'login dashboard';
    }

    public function variable($params)
    {
        echo $params['id'];
        echo "<br>";
        echo $params['user'];
    }

}
```

```php
// General.php :

Route::get('/homepage', function() {
  Route::goto([System\Modules\Homepage\Controllers\Login::class, 'index']);
});

Route::get('/variable/(:num)/(:alpha)', function($id, $user) {
  $params = [
    'id' => $id,
    'user' => $user
  ];
  Route::goto([System\Modules\Homepage\Controllers\Login::class, 'variable'], $params);
});
```

### Another way to call a controller with minimal code instead of Route::goto()

```php
Route::get('/homepage', [System\Modules\Homepage\Controllers\Login::class, 'index']);
```

### Route group with (base path)

```php
Route::group('/admin', function() {
  // map to /admin/input
  Route::get('/input', function() {
    echo 'input user';
  });

  // map to /admin/delete
  Route::get('/delete', function() {
    echo 'delete user';
  });
});
```

### If there is no route defined for a certain location, you can make NSY_Router run a custom callback

```php
Route::error(function() {
  echo '404 :: Not Found';
});
```

If you don't specify an error callback, NSY_Router will just echo `404`.

---

## MVC & HMVC

* The Model View Controller (MVC) design pattern specifies that an application consist of a data model, presentation information, and control information. The pattern requires that each of these be separated into different objects.
* The Hierarchical Model View Controller (HMVC) is an evolution of the MVC pattern used for most web applications today. It came about as an answer to the scalability problems apparent within applications which used MVC.

---

## Introducting to NSY Assets Manager

The easiest & best assets manager in history
made with love by Vikry Yuansah.

How to use it? Simply follow this.

* First, you need to go to `System/Config/`, there are 1 files, that is `Assets.php`.
* `NSY_AssetManager.php` is the core, it is located in `System/Core` folder. `Assets.php` is the controller which regulates assets, if you want to manage the assets, please go to `Assets.php`.

### Create `<meta>` tag

```php
Add::meta('name', 'content');
```

### Create `<link>` tag

```php
Add::link('filename/url_filename', 'attribute_rel', 'attribute_type');
```

### Create `<script>` tag

```php
Add::script('filename/url_filename', 'attribute_type', 'attribute_charset', 'async defer');
```

### You can write any html tags with custom method

```php
Add::custom('anythings');
```

After that, to use it in View, you only need to call the static method name that you created like this

```php
method_name();
```

**For example :**
>
> ```php
> header_assets();
> footer_assets();
> ```

---

## PSR-4 Autoloading

NSY applies the concept of PSR-4 Autoloading. NSY has the `composer.json` file that can be dumped with [composer](https://getcomposer.org/download/) command `composer dump-autoload -o` when creating a folder structure that contains new class files.

Complete information about PSR-4 can be read on the official [PHP-FIG](https://www.php-fig.org/psr/psr-4/) website.

---

## NSY CLI (Command Line Interface)

NSY CLI is a collection of commands to facilitate users in operating NSY. To start, open the `terminal` or `git bash` on your project directory, then install it with :

**Note :**
>
>```text
>If you install the NSY Framework through the `composer create-project`, it automatically includes the NSY CLI.
>```

### NSY CLI Manual Install

Requirements: `bash` and PHP CLI on `PATH` (`php -v` should work). No `wget` needed — migrations run directly through PHP.

* Open Linux Terminal or Git Bash Terminal inside your project directory.
* `sudo chmod +x INSTALL.sh` (use this if you want to install on a linux operating system that requires permission, or if you are a Windows user, then skip this command).

```sh
sudo chmod +x INSTALL.sh
```

* `bash INSTALL.sh` or `./INSTALL.sh`.

```sh
bash INSTALL.sh
```

* Close the `terminal` or `git bash`, & open it again or reset bashrc with the command `source ~/reloader.sh`.

```sh
source ~/reloader.sh
```

* If NSY CLI installer successfully, it should display.

```text
NSY CLI installed
Please close the Terminal & reopen it again
Or
Please reset bashrc with the command 'source ~/reloader.sh'
```

Then if you type command `nsy --hello` it should display.

```text
Welcome to NSY CLI
NSY CLI installed successfully
```

### Command Reference

| Command | Description |
| --- | --- |
| `nsy --help` | Show help |
| `nsy --hello` | Show welcome message |
| `nsy --version` | Show NSY CLI version |
| `nsy --install` | Install or update NSY CLI |
| `nsy --setup` | First-time setup (`.htaccess`, `env.php`, `system.js`, nginx conf) |
| `nsy serve [port] [host]` | Start PHP built-in dev server (default `127.0.0.1:8000`) |
| `nsy dump:autoload` | Run `composer dump-autoload -o` |
| `nsy dump:mysql <db> <user> <pass> [table]` | Dump a MySQL database (optionally a single table) |
| `nsy show:module` | List HMVC modules |
| `nsy show:controller mvc` | List MVC controllers |
| `nsy show:controller hmvc <module>` | List HMVC controllers of a module |
| `nsy show:model mvc` | List MVC models |
| `nsy show:model hmvc <module>` | List HMVC models of a module |
| `nsy show:migrate` | List migration class files |
| `nsy make:module <name>` | Create an HMVC module (`Controllers/Models/Views`) |
| `nsy make:controller mvc <name>` | Create an MVC controller |
| `nsy make:controller hmvc <module> <name>` | Create an HMVC controller |
| `nsy make:model mvc <name>` | Create an MVC model |
| `nsy make:model hmvc <module> <name>` | Create an HMVC model |
| `nsy make:view mvc <name>` | Create an MVC view |
| `nsy make:view hmvc <module> <name>` | Create an HMVC view |
| `nsy make:route <name>` | Create a route file (auto-discovered) |
| `nsy make:middleware <name>` | Create a middleware class scaffold |
| `nsy make:migrate <name>` | Create a timestamped migration class |
| `nsy run:migrate all` | Run every migration (`up`) |
| `nsy run:migrate list` | Pick one migration from a list |
| `nsy run:migrate <name> [up\|down]` | Run a single migration |

> `make:module`, `make:controller`, `make:model`, and `make:view` regenerate Composer autoload automatically when `composer` is available.

### Example of Commands

#### Show list of Migration Class file

```sh
nsy show:migrate
```

Migrations run directly via PHP CLI — no web server or `wget` required. Direction defaults to `up`; append `down` to roll back.

#### Run a Single Migration Class file

```sh
nsy run:migrate <class-name>        # up
nsy run:migrate <class-name> down   # down
```

#### Executes All Migration Class file

```sh
nsy run:migrate all
```

#### Executes the Selected Migration Class file

```sh
nsy run:migrate list

result :
1) crud_table_05062024_163642.php
2) Migration_Test.php
Select a migration class from the above list: 
```

Just type in the number of the migration file you want to run, then press enter key.

```text
result :
1) crud_table_05062024_163642.php
2) Migration_Test.php
Select a migration class from the above list: 1
```

#### Show list of HMVC Modules directory

```sh
nsy show:module
```

#### Show list of HMVC Controller files

```sh
nsy show:controller hmvc <module-directory-name>
```

#### Show list of HMVC Model files

```sh
nsy show:model hmvc <module-directory-name>
```

**Example :**

```sh
nsy show:model hmvc login
```

#### Show list of MVC Controller files

```sh
nsy show:controller mvc
```

#### Show list of MVC Model files

```sh
nsy show:model mvc
```

#### Show welcome message

```sh
nsy --hello
```

#### Show help message

```sh
nsy --help
```

#### Dump mysql database

```sh
nsy dump:mysql <database-name> <username> <password>
```

**Example :**

```sh
nsy dump:mysql db_production root blabla
```

#### Dump mysql database (table only)

```sh
nsy dump:mysql <database-name> <username> <password> <table-name>
```

**Example :**

```sh
nsy dump:mysql db_production root blabla customer_table
```

#### Make HMVC Module

```sh
nsy make:module <module-directory-name>
```

**Example :**

```sh
nsy make:module login
```

#### Make HMVC Controller

```sh
nsy make:controller hmvc <module-directory-name> <controller-name>
```

**Example :**

```sh
nsy make:controller hmvc login controller_login
```

#### Make HMVC Model

```sh
nsy make:model hmvc <module-directory-name> <model-name>
```

**Example :**

```sh
nsy make:model hmvc login model_login
```

#### Make MVC Controller

```sh
nsy make:controller mvc <controller-name>
```

**Example :**

```sh
nsy make:controller mvc controller_login
```

#### Make MVC Model

```sh
nsy make:model mvc <model-name>
```

**Example :**

```sh
nsy make:model mvc model_login
```

#### Make Migration Class

Creates a timestamped migration file in `System/Migrations` (class name equals the file basename).

```sh
nsy make:migrate <class-name>
```

**Example :**

```sh
nsy make:migrate customer_table
# → System/Migrations/customer_table_23092026_153000.php
```

#### Make View File

Creates a Razr view file in the MVC or HMVC view directory.

```sh
nsy make:view mvc <view-name>
nsy make:view hmvc <module-name> <view-name>
```

**Example :**

```sh
nsy make:view mvc view_login
nsy make:view hmvc login view_login
```

#### Make Route File

Creates `System/Routes/<name>.php`. Route files are auto-discovered — no manual registration.

```sh
nsy make:route <route-name>
```

#### Make Middleware Class

Creates a scaffold class in `System/Middlewares`.

> NSY core security is handled by `SecurityMiddleware` (CSRF, sanitize, XSS) and there is **no automatic middleware pipeline** — the generated class is invoked explicitly from a route closure or controller, e.g. `(new MyMiddleware())->handle(fn() => Route::goto([Controller::class, 'method']))`. For built-in protection use `Route::createSecurityMiddleware('strict')`.

```sh
nsy make:middleware <middleware-name>
```

#### Start the Dev Server

Runs the PHP built-in server with a router that maps `/{APP_DIR}/...` (static files + routes) — handy for local development without Apache/nginx.

```sh
nsy serve            # http://127.0.0.1:8000
nsy serve 8080       # custom port
```

#### First time setting up NSY

```sh
nsy --setup
```

#### Generate optimized Composer autoload

```sh
nsy dump:autoload
```

> `make:module`, `make:controller`, and `make:model` also try to run this automatically when `composer` is available.

#### NSY CLI install/update

```sh
nsy --install
```

---

## License

The code is available under the [MIT license](https://github.com/kazuyamarino/nsy/blob/master/LICENSE.txt).

NSY Framework 2019 - 2023.
