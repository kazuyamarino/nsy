# NSY SYSTEM GUIDE
NSY is a simple PHP Framework that works well on MVC or HMVC mode.

Site example :
<a href="https://nsy.kazuyamarino.com/" target="_blank">https://nsy.kazuyamarino.com/</a>

## Usefull Method

### Base URL
Returns your site base URL, as specified in your config file.
```
echo base_url();
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
fetch_json($data);
```

<hr>

### Getting config value
If you want to take values ​​in the config file on the `system/config`, this is the way :

* Get value from `app.php` :
```
config_app('value_name');
```
* Get value from `database.php` :
```
config_db('connection', 'value_name');
```
* Get value from `site.php` :
```
config_site('connection', 'value_name');
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

## License
The code is available under the [MIT license](LICENSE.txt)..
