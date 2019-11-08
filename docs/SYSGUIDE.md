# NSY SYSTEM GUIDE
NSY is a simple PHP Framework that works well on MVC or HMVC mode.

Site example :
<a href="https://nsy.kazuyamarino.com/" target="_blank">https://nsy.kazuyamarino.com/</a>

## Usefull Method

### Base URL
Returns your site base URL, as specified in your config file.
```
echo base_url();

// http://example.com/
```

You can supply segments as a string. Here is a string example:
```
echo base_url("hmvc/action");
```
The above example would return something like: http://example.com/hmvc/action

You can supply a string to a file, such as an image or stylesheet. For example:
```
echo base_url("images/edit.png");
```
This would give you something like: http://example.com/images/edit.png

Redirect method is used to send a raw HTTP header. Here is the example:
```
redirect('hmvc/success');
```
The above example would return redirect url to: http://example.com/hmvc/success

```
redirect_back();
```
The above example would return redirect to previous page.

<hr>

### Fetch to JSON
If you want to display data values ​​in json form, you can do this method :
```
fetch_json(data);

// $id = [1,2,3,4,5];
// echo fetch_json(["data" => $id]);
//
// Will return :
// {
//   "data": [
//    	1,
//    	2,
//    	3,
//    	4,
//    	5
//   ]
// }
```

<hr>

### Getting config value
If you want to take values ​​in the config file on the `system/config`, this is the way :

* Get value from `app.php` :
```
config_app('value_name');
// config_app('app_env');
```
* Get value from `database.php` :
```
config_db('connection', 'value_name');
// config_db('mysql', 'DB_HOST');
```
* Get value from `site.php` :
```
config_site('value_name');
// config_site('sitetitle');
```

<hr>

### Security helper
* For filtering input value :
```
secure_input('attr_name')
```
* Get CSRF Token on form :
```
echo form_csrf_token();
```
* Get CSRF Token/Only Token :
```
echo csrf_token();
```
* Filtering variable from XSS :
```
xss_filter('value');
```
* Allow http :
```
allow_http();
```
* Disallow http :
```
disallow_http();
```
* Remove get parameter :
```
remove_get_parameters('url');
```

<hr>

## The Controllers

### Passing variable to view
If you want to passing variable to view, you can use example below :
```
$d['hello'] = 'Hello';

$d['world'] = 'World';

$this->set($d);
```
The above example will generate variable `$hello` and `$world` in view.

### Load MVC or HMVC view file
Load MVC view file :
```
$this->load_view(null, 'filename');
```

Load HMVC view file :
```
$this->load_view('module-name', 'filename');
```

The PHP superglobals `post()` and `get()` are used to collect form-data.
```
post('hello');

// Same as $_POST['hello'];
```
```
get('hello');

// Same as $_GET['hello'];
```

### Sequence variable
Create a sequence of the named placeholders, e.g. `:id0`, `:id1`, `:id2`. So the code would be:
```
$this->bind('placeholders')->vars('variable')->sequence()

// $ids = [2,3,4];
// $this->bind(":id")->vars($ids)->sequence()
//
// Will return `print_r()` result
//
// Array
// (
//    [0] => :id0,:id1,:id2
//    [1] => Array
//        (
//            [:id0] => 2
//            [:id1] => 3
//            [:id2] => 4
//        )
// )
```

### Instantiate Model class in the controller
Instantiate the Model class in the controller is useful to make it easier for us to give variables to it.
So there is no need to rewrite the instantiate class in another method.

Just have to write it in the contruct method, like this:
```
public function __construct() {
	// Instantiate Model Crud
	$this->m_crud = new m_crud;
}
```
then `$this->m_crud` is the variable.

### Get URI Segment
To help in retrieving data in URIs
```
get_uri_segment(segment number)
```
Example :
```
http://localhost/nsy/hmvc

echo get_uri_segment(1);
// Output is nsy

echo get_uri_segment(2);
// Output is hmvc
```

### Generate Random Number
To generate a sequence of random numbers that correspond to the desired number or prefix.
```
generate_num(prefix, random_number_length, total_number/char_length);
```
Example :
```
echo generate_num();
// Default output NSY-617807

echo generate_num("VYLMA-", 4, 10);
// Output VYLMA-6906
```

### PHP SESSION
Create session :
```
add_session(session_name, value);

Example : add_session('my_name', 'vikry');
```

Show specific session :
```
show_session(session_name);

Example : show_session('my_name');
```
Unset/destroy specific session :
```
unset_session(session_name);

Example : unset_session('my_name');
```

### Simple Ternary
Ternary operator logic is the process of using "`(condition) ? (true return value) : (false return value)`" statements to shorten your if/else structures.
```
ternary(condition, return true, return false)
```
Example :
```
/* most basic usage */
$var = 5;
$var_is_greater_than_two = ternary($var > 2, true, false);
// output returns true
```

### Specify an empty variable or not
In NSY, there is a function to make it easy for users to see whether a value is empty or not in a variable. i.e. only with 2 methods `not_filled()` and `is_filled()`.

`not_filled()`, s to determine a variable that has no value. Example :
```
$var = null;
not_filled($var); // output return true.
```

`is_filled()`, is to determine a variable has a value. Example :
```
$var = 5;
is_filled($var); // output return true.
```

Reference of the method :
```
$random = "";

$var1 = "";
$var2 = " ";
$var3 = false;
$var4 = true;
$var5 = array();
$var6 = null;
$var7 = "0";
$var8 = 0;
$var9 = 0.0;
$var10 = $random;
```

`not_filled()` :
```
$var1 = no value
$var2 = valued
$var3 = no value
$var4 = valued
$var5 = no value
$var6 = no value
$var7 = no value
$var8 = no value
$var9 = no value
$var10 = no value
```

`is_filled()` :
```
$var1 = no value
$var2 = valued
$var3 = no value
$var4 = valued
$var5 = no value
$var6 = no value
$var7 = no value
$var8 = no value
$var9 = no value
$var10 = no value
```


### Get User Agent
This is how to get user agent data with ease.
```
get_ua()
```
Example :
```
$d = get_ua();
print_r($d);

// output
Array
(
    [userAgent] => Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/77.0.3865.90 Chrome/77.0.3865.90 Safari/537.36
    [name] => Google Chrome
    [version] => 77.0.3865.90
    [platform] => Linux
    [pattern] => #(?Version|Chrome|other)[/ ]+(?[0-9.|a-zA-Z.]*)#
)
```


### Aurora File Export
Aurora is a library created to export data into several file formats supported by aurora such as txt, xls, ods, & etc.
```
aurora(file_extension, filename, separator, header, data, string_delimiter);
```
Variable explanation :
1. `file_extension` is a supported file extension by aurora. `(txt, csv, xls, xlsx, & ods)`.
2. `filename` is a filename defined by user.
3. `separator` is a delimiter of each data or table column `(tab, comma, semicolon, space, pipe, & dot)`.
4. `header` is the top or title of each column.
5. `data` is the contents of the table data or record.
6. `string_delimiter` is quote each string `("double" for double quote, & 'single' for singlequote)`.


Example :
```
$header = [ 'Col1', 'Col2', 'Col3' ];

$data = Array
(
    [0] => Array
        (
            [name] => Vikry Yuansah
            [0] => Vikry Yuansah
            [user_name] => vikry
            [1] => vikry
            [user_code] => 1
            [2] => 1
        )

);

aurora('txt', 'nsy_aurora', 'pipe', $header, $data, 'single');

// Will generate nsy_aurora.txt, See output file at `public` folder.

// This is how it looks inside nsy_aurora.txt
'Col1'|'Col2'|'Col3'
'Vikry Yuansah'|'vikry'|'1'
```

<hr>


## The Models

### Primary & Secondary Database Connections
NSY has supported 2 database connections in one running application.

You can see the .env in root. There seems to be a configuration like this :
```
# Define Primary Connection
DB_CONNECTION=
DB_HOST=
DB_PORT=
DB_NAME=
DB_USER=
DB_PASS=

# Define Secondary Connection
DB_CONNECTION_SEC=
DB_HOST_SEC=
DB_PORT_SEC=
DB_NAME_SEC=
DB_USER_SEC=
DB_PASS_SEC=
```

For example, in the model you want to run an sql query on the first database connection :
```
$q = 'SELECT * FROM blabla';
$this->connect()->query($q);
```

Then, if you want to run an sql query on the second database connection :
```
$q = 'SELECT * FROM blabla';
$this->connect_sec()->query($q);
```

### Getting data out of statement in dozens different formats. fetch_all()
That's most interesting function, with most astonishing features. Mostly thanks to its existence one can call PDO a wrapper, as this function can automate many operations otherwise performed manually.

`fetch_all()` returns an array that consists of all the rows returned by the query. From this fact we can make two conclusions:

This function should not be used, if many rows has been selected. In such a case conventional while loop ave to be used, fetching rows one by one instead of getting them all into array at once. "Many" means more than it is suitable to be shown on the average web page.

This function is mostly useful in a modern web application that never outputs data right away during fetching, but rather passes it to template.
You'd be amazed, in how many different formats this function can return data in (and how little an average PHP user knows of them), all controlled by `FETCH...` variables. Some of them are: `FETCH_NUM, FETCH_ASSOC, FETCH_BOTH, FETCH_CLASS, FETCH_KEY_PAIR, FETCH_UNIQUE, FETCH_GROUP, and FETCH_FUNC`.

#### Getting a plain array.
By default, this function will return just simple enumerated array consists of all the returned rows. Row formatting constants, such as `FETCH_NUM, FETCH_ASSOC, FETCH_COLUMN` etc can change the row format.
```
$q = 'SELECT id, name, username FROM users';
$this->connect()->query($q)->style(FETCH_ASSOC)->fetch_all;

// output
Array
(
    [0] => Array
        (
            [id] => 1
            [name] => Vikry Yuansah
            [user_name] => vikry
        )
)
```

#### Getting a column number.
It is often very handy to get plain one-dimensional array right out of the query, if only one column out of many rows being fetched. Here you go:
```
$q = 'SELECT name FROM users';
$this->connect()->query($q)->style(FETCH_COLUMN)->fetch_all;

// output
Array
(
    [0] => Vikry Yuansah
)
```

#### Getting key-value pairs.
Also extremely useful format, when we need to get the same column, but indexed not by numbers in order but by another field. Here goes `FETCH_KEY_PAIR` constant:
```
$q = 'SELECT name FROM users';
$this->connect()->query($q)->style(FETCH_KEY_PAIR)->fetch_all;

// output
Array
(
    [1] => Vikry Yuansah
    [2] => Nayla Syifa
)
```
and many other modes in fetch_all.

### Getting data out of statement. fetch()
It fetches a single row from database, and moves the internal pointer in the result set, so consequent calls to this function will return all the resulting rows one by one.
```
$q = 'SELECT id, name FROM users';
$this->connect()->query($q)->style(FETCH_NUM)->fetch;

// output
Array
(
    [0] => 1
    [1] => Vikry Yuansah
)
```

Another way would be to bind these variables explicitly while setting the proper param type:

Binds a PHP variable to a corresponding named or question mark placeholder in the SQL statement that was used to prepare the statement.

BindValue with PARAM_INT (Defines variable type as integer/number) :
```
$id = [ ':id' => [3, PAR_INT] ];
$q = "SELECT id, name, user_name FROM tbl_users WHERE id = :id";
$this->connect()->query($q)->vars($id)->bind(BINDVAL)->fetch();

// output
Array
(
    [id] => 3
    [0] => 3
    [name] => Tialuna
    [1] => Tialuna
    [user_name] => tia
    [2] => tia
)
```

BindParam with PARAM_STR (Defines the variable type as a text string) :
```
$string = [ ':name' => ['%yuan%', PAR_STR] ];
$q = "SELECT id, name, user_name FROM tbl_users WHERE name LIKE :name";
$this->connect()->query($q)->vars($string)->bind(BINDPAR)->fetch();

// output
Array
(
    [id] => 1
    [0] => 1
    [name] => Vikry Yuansah
    [1] => Vikry Yuansah
    [user_name] => vikry
    [2] => vikry
)
```
Unlike `BINDVAL`, the variable is bound as a reference and will only be evaluated at the time that `exec()` is called.

`BINDPAR` to bind PHP variables to the parameter markers: bound variables pass their value as input and receive the output value, if any, of their associated parameter markers

### Getting data out of statement. fetch_column()
A neat helper function that returns value of the single field of returned row. Very handy when we are selecting only one field:
```
// Getting the name based on id
$id = [ ':id' => 2 ];
$q = "SELECT name FROM tbl_users WHERE id = :id";
$this->connect()->query($q)->vars($id)->fetch_column();

// output
Nayla Syifa
```
```
// getting number of rows in the table utilizing method chaining
$q = "SELECT count(*) FROM tbl_users";
$this->connect()->query($q)->fetch_column();

// output
3 => number of row data
```

### Getting row count
NSY uses PDO. PDO offers a function for returning the number of rows found by the query, `row_count()`, for example:
```
$q = "SELECT * FROM tbl_users";
$this->connect()->query($q)->row_count();

// output
3 => number of row data
```

However, if you want to get the number of affected rows, here is an example:
```
$id = [ ':id' => 2 ];

$q = "DELETE FROM tbl_users WHERE id = :id";
$deleted_data = $this->connect()->vars($id)->query($q)->row_count();

return deleted_data;

// output
1 => number of row data that was deleted
```

### Executes a prepared statement. exec()

Update data :
```
$id = [ ':id' => 2 ];

$q = "UPDATE tbl_users SET name = :name WHERE id = :id";
$this->connect()->query($q)->vars($id)->exec();
```

Delete data :
```
$id = [ ':id' => 2 ];

$q = "DELETE FROM tbl_users WHERE id = :id";
$this->connect()->query($q)->vars($p)->exec();
```

Insert data :
```
$p = [ ':user_name' => 'Harmoni' ];

$q = "INSERT INTO tbl_users (user_name) VALUES (:user_name)";
$this->connect()->query($q)->vars($p)->exec();
```

#### Multi execution
Example :
```
Underconstruction
```

<hr>

## License
The code is available under the [MIT license](https://github.com/kazuyamarino/nsy/blob/master/LICENSE.txt)
