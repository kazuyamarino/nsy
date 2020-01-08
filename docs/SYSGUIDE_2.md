# NSY SYSTEM GUIDE PART 2
NSY is a simple PHP Framework that works well on MVC or HMVC mode.

Site example :
<a href="https://nsy.kazuyamarino.com/" target="_blank">https://nsy.kazuyamarino.com/</a>

<hr>

# Cookie Library

Use this namespace in the controller :
```
use System\Libraries\Cookie;
```

PHP library for handling cookies, NSY has supported the use of Cookie library class in the controller (mvc or hmvc).<br/>
Available methods in this library:

### - Set cookie:

```php
Cookie::set($key, $value, $time);
```

| Attribute | Description | Type | Required | Default
| --- | --- | --- | --- | --- |
| $key | Cookie name. | string | Yes | |
| $value | The data to save. | string | Yes | |
| $time | Expiration time in days. | string | No | 365 |

**# Return** (boolean)

### - Get item from cookie:

```php
Cookie::get($key);
```

| Attribute | Description | Type | Required | Default
| --- | --- | --- | --- | --- |
| $key | Cookie name. | string | No | '' |

**# Return** (mixed|false) → returns cookie value, cookies array or false

### - Extract item from cookie and delete cookie:

```php
Cookie::pull($key);
```

| Attribute | Description | Type | Required | Default
| --- | --- | --- | --- | --- |
| $key | Cookie name. | string | Yes | |

**# Return** (string|false) → item or false when key does not exists

### - Extract item from cookie and delete cookie:

```php
Cookie::destroy($key);
```

| Attribute | Description | Type | Required | Default
| --- | --- | --- | --- | --- |
| $key | Cookie name to destroy. Not set to delete all. | string | No | '' |

**# Return** (boolean)

### - Set cookie prefix:

```php
Cookie::set_prefix($prefix);
```

| Attribute | Description | Type | Required | Default
| --- | --- | --- | --- | --- |
| $prefix | Cookie prefix. | string | Yes | |

**# Return** (boolean)

### - Get cookie prefix:

```php
Cookie::get_prefix();
```

**# Return** (string) → cookie prefix

## Usage

Example of use for this library:

### - Set cookie:

```php
Cookie::set('cookie_name', 'value', 365);
```

### - Get cookie:

```php
Cookie::get('cookie_name');
```

### - Get all cookies:

```php
Cookie::get();
```

### - Pull cookie:

```php
Cookie::pull('cookie_name');
```

### - Destroy one cookie:

```php
Cookie::destroy('cookie_name');
```

### - Destroy all cookies:

```php
Cookie::destroy();
```

### - Set cookie prefix:

```php
Cookie::set_prefix('prefix_');
```

### - Get cookie prefix:

```php
Cookie::get_prefix();
```

<hr>

# Razr - The powerful PHP template engine

Razr is a powerful PHP template engine for PHP, whose syntax was inspired by ASP.NET Razor.<br/>
NSY has supported Razr in the View component. In addition NSY also still supports PHP code in the View component. Either using Razr or PHP, they can run together in one View component!

## Syntax

The Razr syntax uses `@` as special character. It is used to indicate a dynamic statement for the template engine. Within the `@()` notation you may use regular PHP. The following statements are supported.

### Echo data

Use the `@()` notation to echo any PHP data with escaping enabled by default.

**Example**

```html
<h1>@( $title )</h1>
@( 23 * 42 )
@( "<Data> is escaped by default." )
```

**Output**

```html
<h1>Some title</h1>
966
&lt;Data&gt; is escaped by default.
```

### Echo raw data

Use the `@raw()` directive to output any PHP data without escaping.

**Example**

```html
@raw("This will <strong>not</strong> be escaped.")
```

**Output**

```html
This will <strong>not</strong> be escaped.
```

### Variables

You can access single variables and nested variables in arrays/objects using the following dot `.` notation.

```php
array(
    'title' => 'I am the walrus',
    'artist' => array(
        'name' => 'The Beatles',
        'homepage' => 'http://www.thebeatles.com',
    )
)
```

**Example**

```html
<h1>@( $title )</h1>
<p>by @( $artist.name ), @( $artist.homepage )</p>
```

**Output**

```html
<h1>I am the walrus</h1>
<p>by The Beatles, http://www.thebeatles.com</p>
```

### Set variable values

**Example**

```html
@set($msg = "Hello World!")
@( $msg )
```

**Output**

```html
Hello World!
```


### Conditional control structures

Use `@if`, `@elseif`, `@else` for conditional control structures. Use any boolean PHP expression.

**Example**

```html
@set($expression = false)
@if( $expression )
    One.
@elseif ( !$expression )
    Two.
@else
    Three.
@endif
```

**Output**

```html
Two.
```


### Loops

You can use loop statements like `foreach` and `while`.

```html
@foreach($values as $key => $value)
    <p>@( $key ) - @( $value )</p>
@endforeach

@foreach([1,2,3] as $number)
    <p>@( $number )</p>
@endforeach

@while(true)
    <p>Infinite loop.</p>
@endwhile
```

### Include

Extract reusable pieces of markup to an external file using partials and the `@include` directive. You can pass an array of arguments as a second parameter.

**Example**

```html
<section>@include('partial.razr', ['param' => 'parameter'])</section>
```

`partial.razr`:

```html
<p>Partial with @( $param )<p>
```

**Output**

```html
<section><p>Partial with parameter<p><section>
```

### Extending templates with blocks

Use the `@block` directive to define blocks inside a template. Other template files can extend those files and define their own content for the defined blocks without changing the rest of the markup.

**Example**

```html
@include('child.razr', ['param' => 'parameter'])
```

`parent.razr`:

```html
<h1>Parent template</h1>

@block('contentblock')
    <p>Parent content.</p>
@endblock

<p>Parent content outside of the block.</p>
```

`child.razr`:

```html
@extend('parent.razr')

@block('contentblock')
    <p>You can extend themes and overwrite content inside blocks. Paremeters are available as well: @( $param ).</p>
@endblock

```

**Output**

```html
<h1>Parent template</h1>

<p>You can extend themes and overwrite content inside blocks. Paremeters are available as well: parameter.</p>

<p>Parent content outside of the block.</p>
```

<hr>

# NSY FTP Client Library

NSY supports a flexible FTP and SSL-FTP client for PHP. This library provides helpers easy to use to manage the remote files. [nicolab/php-ftp-client](https://github.com/Nicolab/php-ftp-client).

You only need to instantiate the class in the construct method `__contruct`.
[See Instantiate Model class in the controller](https://github.com/kazuyamarino/nsy/blob/master/docs/SYSGUIDE.md#instantiate-model-class-in-the-controller).

#### Login to FTP
```
$this->ftp = new \FtpClient\FtpClient();
$this->ftp->connect('website.com');
$this->ftp->login('admin@website.com', 'password');

// Turns passive mode on (true) or off (false)
$this->ftp->pasv(true);
```

#### Returns the last modified time of the given file
```
$this->ftp->modifiedTime('path/of/file');
```

#### Changes to the parent directory
```
$this->ftp->up();
```

#### Returns a list of files in the given directory
```
$this->ftp->nlist('path/of/directory', true);
```

#### Removes a directory
```
$this->ftp->rmdir('path/of/directory/to/remove');
```

#### Removes a directory (recursive)
```
$this->ftp->rmdir('path/of/directory/to/remove', true);
```

#### Creates a directory
```
$this->ftp->mkdir('path/of/directory/to/create');
```

#### Creates a directory (recursive), creates automaticaly the sub directory if not exist
```
$this->ftp->mkdir('path/of/directory/to/create', true);
```

#### Check if a directory exist
```
$this->ftp->isDir('path/of/directory');
```

#### Check if a directory is empty
```
$this->ftp->isEmpty('path/of/directory');
```

#### Scan a directory and returns the details of each item
```
$this->ftp->scanDir('path/of/directory');
```

#### Returns the total size of the given directory in bytes
```
$this->ftp->dirSize('path/of/directory');
```
### Count method
* count in the current directory
```
$total = $this->ftp->count();
```

* count in a given directory
```
$total = $this->ftp->count('/path/of/directory');
```

* count only the "files" in the current directory
```
$total_file = $this->ftp->count('.', 'file');
```

* count only the "files" in a given directory
```
$total_file = $this->ftp->count('/path/of/directory', 'file');
```

* count only the "directories" in a given directory
```
$total_dir = $this->ftp->count('/path/of/directory', 'directory');
```

* count only the "symbolic links" in a given directory
```
$total_link = $this->ftp->count('/path/of/directory', 'link');
```

#### Downloads a file from the FTP server into a string
```
$this->ftp->getContent('path/of/file');
```
### Upload method
* Uploads a file to the server from a string
```
$this->ftp->putFromString('path/of/file', 'string');
```

* Uploads a file to the server
```
$this->ftp->putFromPath('path/of/file');
```

* upload with the BINARY mode
```
$this->ftp->putAll('source_directory', 'target_directory');
```

* Is equal to
```
$this->ftp->putAll('source_directory', 'target_directory', FTP_BINARY);
```

* or upload with the ASCII mode
```
$this->ftp->putAll('source_directory', 'target_directory', FTP_ASCII);
```

#### Downloads all files from remote FTP directory
```
$this->ftp->getAll('source_directory', 'target_directory', FTP_BINARY);
```

#### Returns a detailed list of files in the given directory
```
$this->ftp->rawlist('path/of/directory');
```

#### Parse raw list
```
$d = $this->ftp->rawlist('path/of/directory');
$e = $this->ftp->parseRawList($d);
```

#### Convert raw info (drwx---r-x ...) to type (file, directory, link, unknown)
```
$this->ftp->rawToType('drwx---r-x');
```

#### Set permissions on a file via FTP
```
$this->ftp->chmod('0775', 'path/of/file');
```

<hr>

# NSY Migrations

Migration is like version control for your database, allowing your team to easily modify and share application database schemes.

Migration is usually paired with the NSY schema builder to easily build your application's database schema. If you have told teammates to manually add columns to their local database schema, you have experienced problems that were resolved by database migration.

How to use migration on NSY, you only need to create the migration class by typing on the Terminal or CMD:
```
migrate <migration-name>
```

**For example**
```
migrate create_database_and_table_supplier
```
And the result will be a file created from the results of the command earlier in the `system/migrations`.
```
└── migrations
       └── create_database_and_table_supplier.php
```
There are 2 methods in the file, namely `up()` and `down()` methods. If you want to run the method `up()` then the command is,
```
migup=class_name

Example : http://localhost/nsy/migup=create_database_and_table_supplier
```

And for `down()`,
```
migdown=class_name

Example : http://localhost/nsy/migdown=drop_table_supplier
```

Well, in that method, you can fill it with some help methods that have been defined by NSY to support migration like the method below:

### Create database
```
$this->connect()->create_db('example_db');
```

### Delete database
```
$this->connect()->drop_db('example_db');
```

### Create table with several columns (mysql/mariadb/mssql)
```
$this->connect()->create_table('example', function() {
	return $this->cols([
		'id' => 'bigint(20) not null',
		'bundle' => 'bigint(20) not null',
		'reader_id' => 'varchar(20) null',
		'trans_time' => 'datetime null',
		'antenna_id' => 'varchar(100) null',
		'tid' => 'varchar(100) null',
		'user_memory' => 'varchar(100) null'
	]);
});
```

### Create table with primary key & unique key (mysql/mariadb/mssql)
```
$this->connect()->create_table('example', function() {
	return $this->cols([
		'id' => 'bigint not null',
		'bundle' => 'bigint not null',
		'reader_id' => 'varchar(20) null',
		'trans_time' => 'datetime null',
		'antenna_id' => 'varchar(100) null',
		'tid' => 'varchar(100) null',
		'user_memory' => 'varchar(100) null',
		$this->primary('id'),
		$this->unique([
			'reader_id', 'trans_time'
		])
	]);
});
```

### Create table with timestamps column e.g. create_date/update_date/additional_date (mysql/mariadb/mssql)
```
$this->connect()->create_table('example', function() {
	return $this->cols([
		'id' => 'bigint not null',
		'bundle' => 'bigint not null',
		'reader_id' => 'varchar(20) null',
		'trans_time' => 'datetime null',
		'antenna_id' => 'varchar(100) null',
		'tid' => 'varchar(100) null',
		'user_memory' => 'varchar(100) null',
		$this->primary('id'),
		$this->unique([
			'reader_id', 'trans_time'
		])
	], $this->timestamps() );
});
```

### Rename table (mysql/mariadb)
```
$this->connect()->rename_table('example', 'newExample');
```

### Rename table (postgre)
```
$this->connect()->alter_rename_table('example', 'newExample');
```

### Rename table (mssql)
```
$this->connect()->sp_rename_table('example', 'newExample');
```

### Delete table if exist (mysql/mariadb)
```
$this->connect()->drop_exist_table('example');
```

### Delete table
```
$this->connect()->drop_table('example');
```

### Add columns (mysql/mariadb/postgre)
```
$this->connect()->add_cols('example', function() {
	return $this->cols([
		'Column1' => 'varchar(20)',
		'Column2' => 'varchar(20)',
		'Column3' => 'varchar(20)'
	]);
});
```

### Add columns (mssql)
```
$this->connect()->add('example', function() {
	return $this->cols([
		'Column1' => 'varchar(20)',
		'Column2' => 'varchar(20)',
		'Column3' => 'varchar(20)'
	]);
});
```

### Delete column (mysql/mariadb/postgre/mssql)
```
$this->connect()->drop_cols('example', function() {
	return $this->cols([
		'Column1',
		'Column2'
	]);
});
```

### Rename columns (mysql/mariadb)
```
$this->connect()->change_cols('example', function() {
	return $this->cols([
		'Column1' => 'NewColumn1',
		'Column2' => 'NewColumn2',
		'Column3' => 'NewColumn3'
	]);
});
```

### Rename columns (postgre)
```
$this->connect()->rename_cols('example', function() {
	return $this->cols([
		'Column1' => 'NewColumn1',
		'Column2' => 'NewColumn2',
		'Column3' => 'NewColumn3'
	]);
});
```

### Rename columns (mssql)
```
$this->connect()->sp_rename_cols('example', function() {
	return $this->cols([
		'Column1' => 'NewColumn1',
		'Column2' => 'NewColumn2',
		'Column3' => 'NewColumn3'
	]);
});
```

### Modify columns datatype (mysql/mariadb)
```
$this->connect()->modify_cols('example', function() {
	return $this->cols([
		'Column1' => 'bigint(12) not null',
		'Column2' => 'bigint(12) not null',
		'Column3' => 'bigint(12) not null'
	]);
});
```

### Modify columns primary and unique key (mysql/mariadb)
```
$this->connect()->modify_cols('example', function() {
	return $this->cols([
		$this->primary([
			'Column1',
			'Column2'
		]),
		$this->unique([
			'Column3',
			'Column4'
		])
	]);
});
```

### Modify columns datatype (mssql/postgre)
```
$this->connect()->alter_cols('example', function() {
	return $this->cols([
		'Column1' => 'char(12) not null',
		'Column2' => 'varchar(12) not null',
		'Column3' => 'datetime not null'
	]);
});
```

### Modify columns primary and unique key (mssql/postgre)
```
$this->connect()->alter_cols('example', function() {
	return $this->cols([
		$this->primary([
			'Column1',
			'Column2'
		]),
		$this->unique([
			'Column3',
			'Column4'
		])
	]);
});
```

# IP Library

Use this namespace in the controller :
```
use System\Libraries\Ip;
```

## Available Methods

Available methods in this library:

### - Get user's IP:

```php
Ip::get();
```

**# Return** (string|false) → user IP or false

### - Validate IP:

```php
Ip::validate($ip);
```

| Attribute | Description | Type | Required | Default
| --- | --- | --- | --- | --- |
| $ip | IP address to be validated. | string | Yes | |

**# Return** (boolean)

## Usage

Example of use for this library:

### - Get user's IP:

```php
Ip::get();
```

### - Validate IP:

```php
$ip = Ip::get();

Ip::validate($ip);
```

# File Library

Use this namespace in the controller :
```
use System\Libraries\File;
```

## Available Methods

Available methods in this library:

### - Check if a file exists in a path or url:

```php
File::exists($file);
```

| Attribute | Description | Type | Required | Default
| --- | --- | --- | --- | --- |
| $file | Path or file url. | string | Yes | |

**# Return** (boolean)

### - Delete file if exists:

```php
File::delete($file);
```

| Attribute | Description | Type | Required | Default
| --- | --- | --- | --- | --- |
| $file | File path. | string | Yes | |

**# Return** (boolean)

### - Create directory if not exists:

```php
File::create_dir($path);
```

| Attribute | Description | Type | Required | Default
| --- | --- | --- | --- | --- |
| $path | Path where to create directory. | string | Yes | |

**# Return** (boolean)

### - Copy directory recursively:

```php
File::copy_dir_recursively($from, $to);
```

| Attribute | Description | Type | Required | Default
| --- | --- | --- | --- | --- |
| $from| Path from copy. | string | Yes | |
| $to| Path to copy. | string | Yes | |

**# Return** (boolean)

### - Delete empty directory:

```php
File::delete_empty_dir($path);
```

| Attribute | Description | Type | Required | Default
| --- | --- | --- | --- | --- |
| $path | Path to delete. | string | Yes | |

**# Return** (boolean)

### - Delete directory recursively:

```php
File::delete_dir_recursively($path);
```

| Attribute | Description | Type | Required | Default
| --- | --- | --- | --- | --- |
| $path | Path to delete. | string | Yes | |

**# Return** (boolean)

### - Get files from directory:

```php
File::get_files_from_dir($path);
```

| Attribute | Description | Type | Required | Default
| --- | --- | --- | --- | --- |
| $path | Path where get file paths. | string | Yes | |

**# Return** (boolean)

## Usage

Example of use for this library:

### - Check if a local file exists:

```php
<?php
File::exists('path/to/file.php');
```

### - Check if a external file exists:

```php
<?php
File::exists('https://raw.githubusercontent.com/Josantonius/PHP-File/master/composer.json');
```

### - Delete a local file:

```php
<?php
File::delete(public_path('file.txt'));
```

### - Create directory:

```php
<?php
File::create_dir(public_path('/test/'));
```

### - Delete empty directory:

```php
<?php
File::delete_empty_dir(public_path('/test/'));
```

### - Delete directory recursively:

```php
<?php
File::delete_dir_recursively(public_path('/test/'));
```

### - Copy directory recursively:

```php
<?php
File::copy_dir_recursively(public_path('/test/'), public_path('/copy/'));
```

### - Get file paths from directory:

```php
<?php
get_class(File::get_files_from_dir(__DIR__));
```

# Curl Library

Use this namespace in the controller :
```
use System\Libraries\Curl;
```

## Available Methods

Available methods in this library:

### - Make request and get response website:

```php
Curl::request($url, $params, $result);
```

| Attribute | Description | Type | Required | Default
| --- | --- | --- | --- | --- |
| $url | Url when get content. | string | Yes | |

| Attribute | Key | Description | Type | Required | Default
| --- | --- | --- | --- | --- | --- |
| $params | | Parameters. | array | No | array() |
| | 'referer' | The referrer URL. | string | No | |
| | 'timeout' | Timeout. | int | No | |
| | 'agent' | Useragent. | string | No | |
| | 'headers' | HTTP headers. | array | No | |
| | 'data' | Parameters to send. | array | No | |
| | 'type' | Type of request. | string | No | |

| Attribute | Description | Type | Required | Default
| --- | --- | --- | --- | --- |
| $result | Returns result as array or object. | string | No | 'array' |

**# Return** (array|object) → response

## Usage

Example of use for this library:

### - Send GET request and obtain response as array:

```php
Curl::request('https://graph.facebook.com/zuck');
```

### - Send GET request and obtain response as object:

```php
Curl::request('https://graph.facebook.com/zuck', false, 'object');
```

### - Send GET request with params and obtain response as array:

```php
$data = [
    'timeout' => 10,
    'referer' => 'http://site.com',
];

Curl::request('https://graph.facebook.com/zuck', $data);
```

### - Send GET request with params and obtain response as object:

```php
$data = [
    'timeout' => 10,
    'referer' => 'http://site.com',
];

Curl::request('https://graph.facebook.com/zuck', $data, 'object');
```

### - Send POST request and obtain response as array:

```php
$data = [
    'type'    => 'post',
    'data'    => array('user' => '123456', 'password' => 'xxxxx'),
    'timeout' => 10,
    'referer' => 'http://' . $_SERVER['HTTP_HOST'],
    'headers' => [
        'Content-Type:application/json',
        'Authorization:0kdm3hzmb4h3cf',
    ],
];

Curl::request('https://graph.facebook.com/zuck', $data);
```

### - Send POST request and obtain response as object:

```php
$data = [
    'type'    => 'post',
    'data'    => array('user' => '123456', 'password' => 'xxxxx'),
    'timeout' => 10,
    'referer' => 'http://' . $_SERVER['HTTP_HOST'],
    'headers' => [
        'Content-Type:application/json',
        'Authorization:0kdm3hzmb4h3cf',
    ],
];

Curl::request('https://graph.facebook.com/zuck', $data, 'object');
```

### - Send PUT request and obtain response as array:

```php
$data = [
    'type'    => 'put',
    'data'    => array('email' => 'new@email.com'),
    'timeout' => 30,
    'referer' => 'http://' . $_SERVER['HTTP_HOST'],
    'headers' => [
        'Content-Type:application/json',
        'Authorization:0kdm3hzmb4h3cf',
    ],
];

Curl::request('https://graph.facebook.com/zuck', $data);
```

### - Send PUT request and obtain response as object:

```php
$data = [
    'type'    => 'put',
    'data'    => array('email' => 'new@email.com'),
    'timeout' => 30,
    'referer' => 'http://' . $_SERVER['HTTP_HOST'],
    'headers' => [
        'Content-Type:application/json',
        'Authorization:0kdm3hzmb4h3cf',
    ],
];

Curl::request('https://graph.facebook.com/zuck', $data, 'object');
```

### - Send DELETE request and obtain response as array:

```php
$data = [

    'type'    => 'delete',
    'data'    => ['userId' => 10],
    'timeout' => 30,
    'referer' => 'http://' . $_SERVER['HTTP_HOST'],
    'headers' => [
        'Content-Type:application/json',
        'Authorization:0kdm3hzmb4h3cf',
    ],
];

Curl::request('https://graph.facebook.com/zuck', $data);
```

### - Send DELETE request and obtain response as object:

```php
$data = [
    'type'    => 'delete',
    'data'    => ['userId' => 10],
    'timeout' => 30,
    'referer' => 'http://' . $_SERVER['HTTP_HOST'],
    'headers' => [
        'Content-Type:application/json',
        'Authorization:0kdm3hzmb4h3cf',
    ],
];

Curl::request('https://graph.facebook.com/zuck', $data, 'object');
```

# Json Library

Use this namespace in the controller :
```
use System\Libraries\Json;
```

## Available Methods

Available methods in this library:

### - Creating JSON file from array:

```php
Json::array_to_file($array, $file);
```

| Attribute | Description | Type | Required | Default
| --- | --- | --- | --- | --- |
| $array | Array to be converted to JSON. | array | Yes | |
| $file | Path to the file. | string | Yes | |

**# Return** (boolean)

### - Save to array the JSON file content:

```php
Json::file_to_array($file);
```

| Attribute | Description | Type | Required | Default
| --- | --- | --- | --- | --- |
| $file | Path or external url to JSON file. | string | Yes | |

**# Return** (array|false)

### - Check for errors:

```php
JsonLastError::check();
```

**# Return** (array|null) → Null if there are no errors or array with state  code and error message.

### - Get collection of JSON errors:

```php
JsonLastError::get_collection();
```

**# Return** (array) → Collection of JSON errors.

## Usage

Example of use for this library:

### - Creating JSON file from array:

```php

$array = [
	'name'  => 'Josantonius',
    'email' => 'info@josantonius.com',
    'url'   => 'https://github.com/josantonius/PHP-Json'
];

$pathfile = public_path('file.json');

Json::array_to_file($array, $pathfile);

```

### - Save to array the JSON file content:

```php
$pathfile = public_path('file.json');

$array = Json::file_to_array($pathfile);

```

### - Check for errors:

```php
$lastError = JsonLastError::check();

if (!is_null($lastError)) {
    var_dump($lastError);
}
```

### - Get collection of JSON errors:

```php
$jsonLastErrorCollection = JsonLastError::get_collection();
```

# LoadTime Library

Use this namespace in the controller :
```
use System\Libraries\LoadTime;
```

## Available Methods

Available methods in this library:

### - Set initial time:

```php
LoadTime::start();
```

**# Return** (float) → microtime

### - Set end time:

```php
LoadTime::end();
```

**# Return** (float) → seconds

### - Check if the timer has been started:

```php
LoadTime::is_active();
```

**# Return** (boolean)

## Usage

Example of use for this library:

```php
<?php
LoadTime::start();

for ($i=0; $i < 100000; $i++) {
    // print_r($i . ' ');
}

print_r('Script executed in: ' . LoadTime::end() . ' seconds.');

/* Script executed in: 0.0012 seconds. */
```

# LanguageCode Library

Use this namespace in the controller :
```
use System\Libraries\LanguageCode;
```

## Available Methods

Available methods in this library:

### - Get all language codes as array:

```php
LanguageCode::get();
```

**# Return** (array) → language codes and language names

### - Get language name from language code:

```php
LanguageCode::get_language_from_code($languageCode);
```

| Attribute | Description | Type | Required | Default
| --- | --- | --- | --- | --- |
| $languageCode | Language code, e.g. 'es'. | string | Yes | |

**# Return** (tring|false) → country name

### - Get language code from language name:

```php
LanguageCode::get_code_from_language($languageName);
```

| Attribute | Description | Type | Required | Default
| --- | --- | --- | --- | --- |
| $languageName | Language name, e.g. 'Spanish'. | string | Yes | |

**# Return** (tring|false) → language code

## Usage

Example of use for this library:
```php
LanguageCode::get();
```

# String Library

Use this namespace in the controller :
```
use System\Libraries\Str;
```

## Available Methods

### - Check if the string starts with a certain value:

```php
Str::starts_with($search, $string);
```

| Attribute | Description | Type | Required | Default
| --- | --- | --- | --- | --- |
| $search | The string to search. | string | Yes | |
| $string | The string where search. | string | Yes | |

**# Return** (boolean)

### - Check if the string ends with a certain value:

```php
Str::ends_with($search, $string);
```

| Attribute | Description | Type | Required | Default
| --- | --- | --- | --- | --- |
| $search | The string to search. | string | Yes | |
| $string | The string where search. | string | Yes | |

**# Return** (boolean)

## Usage

Example of use for this library:

### - Check if the string starts with a certain value:

```php
Str::starts_with("Hello", "Hello world");
```

### - Check if the string ends with a certain value:

```php
Str::ends_with("world", "Hello World");
```

# ImageResize Library

Use this namespace in the controller :
```
use System\Libraries\ImageResize;
```

Resize
------

To scale an image, in this case to half it's size (scaling is percentage based):

```php
$image = new ImageResize('image.jpg');
$image->scale(50);
$image->save('image2.jpg');
```

To resize an image according to one dimension (keeping aspect ratio):

```php
$image = new ImageResize('image.jpg');
$image->resize_to_height(500);
$image->save('image2.jpg');

$image = new ImageResize('image.jpg');
$image->resize_to_width(300);
$image->save('image2.jpg');
```

To resize an image according to a given measure regardingless its orientation (keeping aspect ratio):

```php
$image = new ImageResize('image.jpg');
$image->resize_to_long_side(500);
$image->save('image2.jpg');

$image = new ImageResize('image.jpg');
$image->resize_to_short_side(300);
$image->save('image2.jpg');
```

To resize an image to best fit a given set of dimensions (keeping aspet ratio):
```php
$image = new ImageResize('image.jpg');
$image->resize_to_best_fit(500, 300);
$image->save('image2.jpg');
```

All resize functions have ```$allow_enlarge``` option which is set to false by default.
You can enable by passing ```true``` to any resize function:
```php
$image = new ImageResize('image.jpg');
$image->resize(500, 300, $allow_enlarge = True);
$image->save('image2.jpg');
```

If you are happy to handle aspect ratios yourself, you can resize directly:

```php
$image = new ImageResize('image.jpg');
$image->resize(800, 600);
$image->save('image2.jpg');
```

This will cause your image to skew if you do not use the same width/height ratio as the source image.

Crop
----

To to crop an image:

```php
$image = new ImageResize('image.jpg');
$image->crop(200, 200);
$image->save('image2.jpg');
```

This will scale the image to as close as it can to the passed dimensions, and then crop and center the rest.

In the case of the example above, an image of 400px &times; 600px will be resized down to 200px &times; 300px, and then 50px will be taken off the top and bottom, leaving you with 200px &times; 200px.

Crop modes:

Few crop mode options are available in order for you to choose how you want to handle the eventual exceeding width or height after resizing down your image.
The default crop mode used is the `CROPCENTER`.
As a result those pieces of code are equivalent:

```php
$image = new ImageResize('image.jpg');
$image->crop(200, 200);
$image->save('image2.jpg');
```

```php
$image = new ImageResize('image.jpg');
$image->crop(200, 200, true, ImageResize::CROPCENTER);
$image->save('image2.jpg');
```

In the case you have an image of 400px &times; 600px and you want to crop it to 200px &times; 200px the image will be resized down to 200px &times; 300px, then you can indicate how you want to handle those 100px exceeding passing the value of the crop mode you want to use.

For instance passing the crop mode `CROPTOP` will result as 100px taken off the bottom leaving you with 200px &times; 200px.


```php
$image = new ImageResize('image.jpg');
$image->crop(200, 200, true, ImageResize::CROPTOP);
$image->save('image2.jpg');
```

On the contrary passing the crop mode `CROPBOTTOM` will result as 100px taken off the top leaving you with 200px &times; 200px.

```php
$image = new ImageResize('image.jpg');
$image->crop(200, 200, true, ImageResize::CROPBOTTOM);
$image->save('image2.jpg');
```

Freecrop:

There is also a way to define custom crop position.
You can define $x and $y in ```freecrop``` method:

```php
$image = new ImageResize('image.jpg');
$image->freecrop(200, 200, $x =  20, $y = 20);
$image->save('image2.jpg');
```

Loading and saving images from string
-------------------------------------

To load an image from a string:

```php
$image = ImageResize::create_from_string(base64_decode('R0lGODlhAQABAIAAAAQCBP///yH5BAEAAAEALAAAAAABAAEAAAICRAEAOw=='));
$image->scale(50);
$image->save('image.jpg');
```

You can also return the result as a string:

```php
$image = ImageResize::create_from_string(base64_decode('R0lGODlhAQABAIAAAAQCBP///yH5BAEAAAEALAAAAAABAAEAAAICRAEAOw=='));
$image->scale(50);
echo $image->();
```

Magic `__toString()` is also supported:

```php
$image = ImageResize::create_from_string(base64_decode('R0lGODlhAQABAIAAAAQCBP///yH5BAEAAAEALAAAAAABAAEAAAICRAEAOw=='));
$image->resize(10, 10);
echo (string)$image;
```

Displaying
----------

As seen above, you can call `$image->save('image.jpg');`

To render the image directly into the browser, you can call `$image->output()`;

Image Types
-----------

When saving to disk or outputting into the browser, the script assumes the same output type as input.

If you would like to save/output in a different image type, you need to pass a (supported) PHP [`IMAGETYPE_`* constant](http://www.php.net/manual/en/image.constants.php):

- `IMAGETYPE_GIF`
- `IMAGETYPE_JPEG`
- `IMAGETYPE_PNG`

This allows you to save in a different type to the source:

```php
$image = new ImageResize('image.jpg');
$image->resize(800, 600);
$image->save('image.png', IMAGETYPE_PNG);
```

Quality
-------

The properties `$quality_jpg` and `$quality_png` are available for you to configure:

```php
$image = new ImageResize('image.jpg');
$image->quality_jpg = 100;
$image->resize(800, 600);
$image->save('image2.jpg');
```

By default they are set to 85 and 6 respectively. See the manual entries for [`imagejpeg()`](http://www.php.net/manual/en/function.imagejpeg.php) and [`imagepng()`](http://www.php.net/manual/en/function.imagepng.php) for more info.

You can also pass the quality directly to the `save()`, `output()` and `()` methods:

```php
$image = new ImageResize('image.jpg');
$image->crop(200, 200);
$image->save('image2.jpg', null, 100);

$image = new ImageResize('image.jpg');
$image->resize_to_width(300);
$image->output(IMAGETYPE_PNG, 4);

$image = new ImageResize('image.jpg');
$image->scale(50);
$result = $image->get_image_as_string(IMAGETYPE_PNG, 4);
```

We're passing `null` for the image type in the example above to skip over it and provide the quality. In this case, the image type is assumed to be the same as the input.

Interlacing
-----------

By default, [image interlacing](http://php.net/manual/en/function.imageinterlace.php) is turned on. It can be disabled by setting `$interlace` to `0`:

```php
$image = new ImageResize('image.jpg');
$image->scale(50);
$image->interlace = 0;
$image->save('image2.jpg');
```

Chaining
--------

When performing operations, the original image is retained, so that you can chain operations without excessive destruction.

This is useful for creating multiple sizes:

```php
$image = new ImageResize('image.jpg');
$image
    ->scale(50)
    ->save('image2.jpg')

    ->resize_to_width(300)
    ->save('image3.jpg')

    ->crop(100, 100)
    ->save('image4.jpg')
;
```

Exceptions
--------

ImageResize throws ImageResizeException for it's own for errors. You can catch that or catch the general \Exception which it's extending.

It is not to be expected, but should anything go horribly wrong mid way then notice or warning Errors could be shown from the PHP GD and Image Functions (http://php.net/manual/en/ref.image.php)

```php
try{
    $image = new ImageResize(null);
    echo "This line will not be printed";
} catch (ImageResizeException $e) {
    echo "Something went wrong" . $e->getMessage();
}
```

# Carbon Library

Use this namespace in the controller :
```
use Carbon\Carbon;
```
Carbon DateTime, [Carbon Documentation](https://carbon.nesbot.com/docs/)

<hr>

## License
The code is available under the [MIT license](https://github.com/kazuyamarino/nsy/blob/master/LICENSE.txt)
