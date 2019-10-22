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

<hr>


## The Models

### Under Construction...

## License
The code is available under the [MIT license](https://github.com/kazuyamarino/nsy/blob/master/LICENSE.txt)
