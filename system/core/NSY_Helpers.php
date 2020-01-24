<?php
/*
* This is the core of NSY Helpers.
* Attention, don't try to change the structure of the code, delete, or change.
* Because there is some code connected to the NSY system. So, be careful.
*/

/**
* Use NSY_XSS_Filter class
*/
use System\Core\NSY_XSS_Filter;

/**
* Use NSY_CSRF class
*/
use System\Core\NSY_CSRF;

/**
* Use NSY_Desk class
*/
use System\Core\NSY_Desk;

/**
* Use library
*/
use System\Libraries\Str; // String Class
use System\Libraries\LoadTime; // LoadTime Class
use System\Libraries\Ip; // Ip Class
use System\Libraries\Json; // Json Class
use System\Libraries\JsonLastError; // JsonLastError Class
use System\Libraries\LanguageCode; // LanguageCode Class
use System\Libraries\Validate; // Validate Class

if (! function_exists('public_path')) {
	/**
	* Define public_path() method, get the fullpath 'public' directory
	* @param  string $url
	* @return string
	*/
	function public_path($url = null)
	{
		if ( is_filled($url) ) {
			if ( is_filled(config_app('public_dir')) ) {
				return __DIR__ . '/../../' . config_app('public_dir') . '/' . $url;
			} else {
				return __DIR__ . '/../../' . $url;
			}
		} else {
			if ( is_filled(config_app('public_dir')) ) {
				return __DIR__ . '/../../' . config_app('public_dir');
			} else {
				return __DIR__ . '/../../';
			}
		}
	}
}

if (! function_exists('img_url')) {
	/**
	* Define img_url method, get img directory location on the 'public' directory
	* @param  string $url
	* @return string
	*/
	function img_url($url = null)
	{
		if ( is_filled($url) ) {
			return IMG_DIR . $url;
		} else {
			return IMG_DIR;
		}
	}
}

if (! function_exists('js_url')) {
	/**
	* Define js_url method, get js directory location on the 'public' directory
	* @param  string $url
	* @return string
	*/
	function js_url($url = null)
	{
		if ( is_filled($url) ) {
			return JS_DIR . $url;
		} else {
			return JS_DIR;
		}
	}
}

if (! function_exists('css_url')) {
	/**
	* Define css_url method, get css directory location on the 'public' directory
	* @param  string $url
	* @return string
	*/
	function css_url($url = null)
	{
		if ( is_filled($url) ) {
			return CSS_DIR . $url;
		} else {
			return CSS_DIR;
		}
	}
}

/**
* Define base_url() method, get base url with default project directory
* @param  string $url
* @return string
*/
function base_url($url = null)
{
	// set the default application or project directory
	$APP_DIR = config_app('app_dir');

	if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' || $_SERVER['SERVER_PORT'] == 443) {
		// if default application or project directory undefined
		if(empty($APP_DIR) || is_null($APP_DIR)) {
			// then get this result
			// site address (https) without application directory
			return 'https://' . $_SERVER['HTTP_HOST'] . '/' . $url;
		} else {
			// else if default application or project directory defined then get this result
			// site address (https) with application directory
			return 'https://' . $_SERVER['HTTP_HOST'] . '/' . $APP_DIR . '/' . $url;
		}
	} else {
		// if default application or project directory undefined
		if(empty($APP_DIR) || is_null($APP_DIR)) {
			// then get this result
			// site address (http) without application directory
			return 'http://' . $_SERVER['HTTP_HOST'] . '/' . $url;
		} else {
			// else if default application or project directory defined then get this result
			// site address (http) with application directory
			return 'http://' . $_SERVER['HTTP_HOST'] . '/' . $APP_DIR . '/' . $url;
		}
	}
}

// ------------------------------------------------------------------------

/**
* Get config value from system/config/Database.php
* @param  string|int $d1
* @param  string|int $d2
* @return array
*/
function config_db($d1 = null,$d2 = null)
{
	$database = include __DIR__ . '/../config/Database.php';
	if (not_filled($d2) ) {
		return $database[$d1];
	} else {
		return $database['connections'][$d1][$d2];
	}
}

/**
* Get config value from system/config/Database.php
* @param  string|int $d1
* @param  string|int $d2
* @return array
*/
function config_db_sec($d1 = null,$d2 = null)
{
	$database = include __DIR__ . '/../config/Database.php';
	if (not_filled($d2) ) {
		return $database[$d1];
	} else {
		return $database['connections_sec'][$d1][$d2];
	}
}

// ------------------------------------------------------------------------

/**
* Get config value from system/config/app.php
* @param  string|int $d1
* @return array
*/
function config_app($d1 = null)
{
	$app = include __DIR__ . '/../config/App.php';

	return $app[$d1];
}

// ------------------------------------------------------------------------

/**
* Get config value from system/config/site.php
* @param  string|int $d1
* @return array
*/
function config_site($d1 = null)
{
	$site = include __DIR__ . '/../config/Site.php';

	return $site[$d1];
}

// ------------------------------------------------------------------------

if (! function_exists('redirect')) {
	/**
	* Method for Redirect to specified URI
	* @param  string $url
	* @return void
	*/
	function redirect($url = null)
	{
		header('location:'. base_url($url));
		exit();
	}
}

if (! function_exists('redirect_back')) {
	/**
	* Redirect Back URI
	* @return void
	*/
	function redirect_back()
	{
		header('location: ' . $_SERVER['HTTP_REFERER']);
		exit();
	}
}

// ------------------------------------------------------------------------

if (! function_exists('fetch_json')) {
	/**
	* Fetch data to json format
	* @param  array $data
	* @param  int $status
	* @return string
	*/
	function fetch_json($data = array(), $status = null)
	{
		$json_data = $data;
		$json_result = json_encode($json_data);

		http_response_code($status);
		return $json_result;
	}
}

// ------------------------------------------------------------------------

if (! function_exists('secure_input')) {
	/**
	* Secure Input Element
	* @param  string $data
	* @return string
	*/
	function secure_input($data = null)
	{
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);

		return $data;
	}
}

if (! function_exists('secure_form')) {
	/**
	* Secure Form
	* @param  string $form
	* @return void
	*/
	function secure_form($form = null)
	{
		if (is_array($form) || is_object($form)) {
			foreach ($form as $key => $value) {
				$form[$key] = $this->secure_input($value);
			}
		}
	}
}

// ------------------------------------------------------------------------

if (! function_exists('csrf_token')) {
	/**
	* Return only CSRF Token
	* @return string
	*/
	function csrf_token()
	{
		if(config_app('csrf_token') === 'true') {
			$csrf_token = NSY_CSRF::generate('csrf_token');

			return $csrf_token;
		} elseif(config_app('csrf_token') === 'false') {
			$var_msg = "CSRF Token Protection must be set <strong><i>true</i></strong></p><p>See <strong>system/config/app.php</strong>";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}
	}
}

if (! function_exists('form_csrf_token')) {
	/**
	* Return CSRF Input form with Token
	* @return string
	*/
	function form_csrf_token()
	{
		if(config_app('csrf_token') === 'true') {
			$csrf_token = NSY_CSRF::generate('csrf_token');

			return '<input type="hidden" name="csrf_token" value=' . $csrf_token . '">';
		} elseif(config_app('csrf_token') === 'false') {
			$var_msg = "CSRF Token Protection must be set <strong><i>true</i></strong></p><p>See <strong>system/config/app.php</strong>";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}
	}
}

// ------------------------------------------------------------------------

if (! function_exists('xss_filter')) {
	/**
	* XSS Filter
	* @param  string $value
	* @return string
	*/
	function xss_filter($value = null)
	{
		$xss_filter = new NSY_XSS_Filter();
		$string = $xss_filter->filter_it($value);

		return $string;
	}
}

if (! function_exists('allow_http')) {
	/**
	* Allow http
	* @return void
	*/
	function allow_http()
	{
		$allow_http = new NSY_XSS_Filter();
		$func = $allow_http->allow_http();
	}
}

if (! function_exists('disallow_http')) {
	/**
	* Disallow http
	* @return void
	*/
	function disallow_http()
	{
		$disallow_http = new NSY_XSS_Filter();
		$func = $disallow_http->disallow_http();
	}
}

if (! function_exists('remove_get_parameters')) {
	/**
	* Remove url get parameter
	* @param  string $url
	* @return string
	*/
	function remove_get_parameters($url = null)
	{
		$remove_get_parameters = new NSY_XSS_Filter();
		$func = $remove_get_parameters->remove_get_parameters($url);

		return $func;
	}
}

// ------------------------------------------------------------------------

if (! function_exists('ternary')) {
	/**
	* PHP Shorthand If/Else Using Ternary Operators
	* @param  string|int $condition
	* @param  string|int $result_one
	* @param  string|int $result_two
	* @return string|int
	*/
	function ternary($condition = null, $result_one = null, $result_two = null)
	{
		$result = ($condition ? $result_one : $result_two);

		return $result;
	}
}

// ------------------------------------------------------------------------

if (! function_exists('not_filled')) {
	/**
	* Function for basic field validation (present and neither empty nor only white space
	* @param  string|int|array $str
	* @return string|int|array
	*/
	function not_filled($str = null)
	{
		if (!empty($str)) {
			return false;
			exit();
		} else {
			if (is_array($str)) {
				return (!isset($str) || empty($str));
			} else {
				return (!isset($str) || $str == '' || empty($str));
			}
		}
	}
}

if (! function_exists('is_filled')) {
	/**
	* Function for basic field validation (present and neither filled nor not empty)
	* @param  string|int|array $str
	* @return string|int|array
	*/
	function is_filled($str = null)
	{
		if (!isset($str)) {
			return false;
			exit();
		} else {
			if (is_array($str)) {
				return (isset($key) || !empty($str));
			} else {
				return (isset($key) || !empty($str));
			}
		}
	}
}

// ------------------------------------------------------------------------

if (! function_exists('aurora')) {
	/**
	* Aurora File Exporter
	* @param  string $ext
	* @param  string $name
	* @param  string $sep
	* @param  string $h
	* @param  string $d
	* @param  string $s
	* @return string
	*/
	function aurora($ext = null, $name = null, $sep = null, $h = null, $d = null, $s = null)
	{
		if (not_filled($ext) ) {
			$var_msg = "File extension not yet filled \naurora(<strong><i>file_extension</i></strong>, filename, separator, header, data, string_delimiter);";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		} elseif (not_filled($name) ) {
			$var_msg = "Filename not yet filled \naurora(file_extension, <strong><i>filename</i></strong>, separator, header, data, string_delimiter);";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		} elseif (not_filled($sep) ) {
			$var_msg = "Separator not yet filled \naurora(file_extension, filename, <strong><i>separator</i></strong>, header, data, string_delimiter);";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		} elseif (not_filled($h) ) {
			$var_msg = "Header of the table undefined \naurora(file_extension, filename, separator, <strong><i>header</i></strong>, data, string_delimiter);";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		} elseif (not_filled($d) ) {
			$var_msg = "Record of data empty or unreadable \naurora(file_extension, filename, separator, header, <strong><i>data</i></strong>, string_delimiter);";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		} else {
			// export filename
			$filename  = $name;

			// separator
			if ($sep == 'tab' ) {
				$separator = "\011";
			} elseif ($sep == 'comma' ) {
				$separator = "\054";
			} elseif ($sep == 'semicolon' ) {
				$separator = "\073";
			} elseif ($sep == 'space' ) {
				$separator = "\040";
			} elseif ($sep == 'dot' ) {
				$separator = "\056";
			} elseif ($sep == 'pipe' ) {
				$separator = "\174";
			} else {
				$var_msg = "There is no such separator name (<strong>example:</strong> tab, comma, semicolon, space, pipe, &amp; dot) \naurora(file_extension, filename, <strong><i>separator</i></strong>, header, data, string_delimiter);";
				NSY_Desk::static_error_handler($var_msg);
				exit();
			}

			// string delimiter (double = "" & single = '')
			if ($s == 'double' ) {
				$s = "\042";
			} elseif ($s == 'single' ) {
				$s = "\047";
			} elseif ($s == null ) {
				$s = null;
			} else {
				$var_msg = "There is no such string delimiter name (<strong>example:</strong> \042double\042 for double quote, &amp; \047single\047 for singlequote) \naurora(file_extension, filename, separator, header, data, <strong><i>string_delimiter</i></strong>);";
				NSY_Desk::static_error_handler($var_msg);
				exit();
			}

			// header file text (.txt)
			if ($ext == 'txt' || $ext == 'csv' || $ext == 'xls' || $ext == 'xlsx' || $ext == 'ods' ) {
				// display header
				$i = 0;
				$len_h = count($h);
				foreach ( $h as $key_h => $val_h ) {
					if ($i == $len_h - 1) {
						$roles[] = $s.$val_h.$s;
					} else {
						$roles[] = $s.$val_h.$s.$separator;
					}
					$i++;
				}

				// newline
				$roles[] = "\r\n";

				// display records
				foreach ( $d as $key_d => $val_d ) {
					for ($x = 0; $x <= $len_h - 1; $x++) {
						if ($x == $len_h - 1) {
							$roles[] = $s.$val_d[$x].$s;
						} else {
							$roles[] = $s.$val_d[$x].$s.$separator;
						}
					}

					// newline
					$roles[] = "\r\n";
				}

				// return $roles;
				$file = $filename.'.'.$ext;
				$data = $roles;
				file_put_contents($file, $data);
			} else {
				$var_msg = "There is no such file extension name (<strong>example:</strong> txt, csv, xls, xlsx, &amp; ods) \naurora(<strong><i>file_extension</i></strong>, filename, separator, header, data, string_delimiter);";
				NSY_Desk::static_error_handler($var_msg);
				exit();
			}
		}
	}
}

// ------------------------------------------------------------------------

if (! function_exists('get_ua')) {
	/**
	* User Agent
	* @return array
	* try it :
	* $ua = get_ua();
	* echo $ua['name'];
	* echo '<br>';
	* echo $ua['version'];
	* echo '<br>';
	* echo $ua['platform'];
	* echo '<br>';
	* echo $ua['userAgent'];
	*/
	// http://www.php.net/manual/en/function.get-browser.php#101125
	function get_ua()
	{
		$u_agent = $_SERVER['HTTP_USER_AGENT'];
		$bname = 'Unknown';
		$platform = 'Unknown';
		$version= "";
		// First get the platform?
		if (preg_match('/Android/i', $u_agent)) {
			$platform = 'Android';
		} elseif (preg_match('/linux/i', $u_agent)) {
			$platform = 'Linux';
		} elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
			$platform = 'Macintosh';
		} elseif (preg_match('/windows|win32/i', $u_agent)) {
			$platform = 'Windows';
		}
		// Next get the name of the useragent yes seperately and for good reason
		if(preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
			$bname = 'Internet Explorer';
			$ub = "MSIE";
		} elseif(preg_match('/Firefox/i', $u_agent)) {
			$bname = 'Mozilla Firefox';
			$ub = "Firefox";
		} elseif(preg_match('/OPR/i', $u_agent) && !preg_match('/OPR1/i', $u_agent)) {
			$bname = 'Opera';
			$ub = "OPR";
		} elseif(preg_match('/Chrome/i', $u_agent)) {
			$bname = 'Google Chrome';
			$ub = "Chrome";
		} elseif(preg_match('/Safari/i', $u_agent)) {
			$bname = 'Apple Safari';
			$ub = "Safari";
		} elseif(preg_match('/Netscape/i', $u_agent)) {
			$bname = 'Netscape';
			$ub = "Netscape";
		}
		// finally get the correct version number
		$known = array('Version', $ub, 'other');
		$pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		if (!preg_match_all($pattern, $u_agent, $matches)) {
			// we have no matching number just continue
		}
		// see how many we have
		$i = count($matches['browser']);
		if ($i != 1) {
			//we will have two since we are not using 'other' argument yet
			//see if version is before or after the name
			if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
				$version= $matches['version'][0];
			} else {
				$version= $matches['version'][1];
			}
		} else {
			$version= $matches['version'][0];
		}
		// check if we have a number
		if ($version==null || $version=="") {$version="?";
		}

		return array(
			'userAgent' => $u_agent,
			'name'      => $bname,
			'version'   => $version,
			'platform'  => $platform,
			'pattern'    => $pattern
		);
	}
}

// ------------------------------------------------------------------------

if (! function_exists('array_flatten')) {
	/**
	* PHP array_flatten() function. Convert a multi-dimensional array into a single-dimensional array
	* https://gist.github.com/SeanCannon/6585889#gistcomment-2922278
	* @param  array $items
	* @return array
	*/
	function array_flatten($items)
	{
		if (! is_array($items)) {
			return [$items];
		}

		return array_reduce(
			$items, function ($carry, $item) {
				return array_merge($carry, array_flatten($item));
			}, []
		);
	}
}

// ------------------------------------------------------------------------

if (! function_exists('generate_num')) {
	/**
	* Create Random Number
	* @param  string  $prefix
	* @param  integer $id_length
	* @param  integer $num_length
	* @return integer|string
	*/
	function generate_num($prefix = 'NSY-', $id_length = 6, $num_length = 10)
	{
		$zeros = str_pad(null, $id_length, 0, STR_PAD_LEFT);
		$nines = str_pad(null, $id_length, 9, STR_PAD_LEFT);

		$ids = str_pad(mt_rand($zeros, $nines), $num_length, $prefix, STR_PAD_LEFT);

		return $ids;
	}
}

// ------------------------------------------------------------------------

if (! function_exists('get_uri_segment')) {
	/**
	* Get URI Segment
	* @param  integer $key
	* @return string
	*/
	function get_uri_segment($key = null)
	{
		$uriSegments = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

		if (array_key_exists($key, $uriSegments)) {
			return $uriSegments[$key];
		} else {
			$var_msg = "Segment does not exist";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}
	}
}

// ------------------------------------------------------------------------

/**
* Get application version
* @return string
*/
function get_version()
{
	return VERSION;
}

/**
* Get application codename
* @return string
*/
function get_codename()
{
	return CODENAME;
}

/**
* Get application language code
* @return string
*/
function get_lang_code()
{
	return LANGUAGE_CODE;
}

/**
* Get open graph prefix
* @return string
*/
function get_og_prefix()
{
	return OG_PREFIX;
}

/**
* Get site title
* @return string
*/
function get_title()
{
	return SITETITLE;
}

/**
* Get site description
* @return string
*/
function get_desc()
{
	return SITEDESCRIPTION;
}

/**
* Get site keywords
* @return string
*/
function get_keywords()
{
	return SITEKEYWORDS;
}

/**
* Get site author
* @return string
*/
function get_author()
{
	return SITEAUTHOR;
}

/**
* Get session prefix
* @return string
*/
function get_session_prefix()
{
	return SESSION_PREFIX;
}

/**
* Get site email
* @return string
*/
function get_site_email()
{
	return SITEEMAIL;
}

// ------------------------------------------------------------------------

if (! function_exists('str_starts_with')) {
	/**
	 * Check if the string starts with a certain value
	 * @param  string $search
	 * @param  string $string
	 * @return boolean
	 */
	function str_starts_with($search = '', $string = '')
	{
		$str = Str::starts_with($search, $string);

		return $str;
	}
}

if (! function_exists('str_ends_with')) {
	/**
	 * Check if the string ends with a certain value
	 * @param  string $search
	 * @param  string $string
	 * @return boolean
	 */
	function str_ends_with($search = '', $string = '')
	{
		$str = Str::ends_with($search, $string);

		return $str;
	}
}

// ------------------------------------------------------------------------

if (! function_exists('load_time')) {
	/**
	 * Calculate load time of pages or scripts
	 * Set initial time.
	 * @return boolean
	 */
	function load_time()
	{
		$timestart = LoadTime::start();

		return $timestart;
	}
}

if (! function_exists('end_time')) {
	/**
	 * Calculate end load time of pages or scripts
	 * Set end time.
	 * @return boolean
	 */
	function end_time()
	{
		$timestart = LoadTime::end();

		return $timestart;
	}
}

if (! function_exists('is_active_time')) {
	/**
	 * Check if the timer has been started
	 * @return boolean
	 */
	function is_active_time()
	{
		$timestart = LoadTime::is_active();

		return $timestart;
	}
}

// ------------------------------------------------------------------------

if (! function_exists('get_ip')) {
	/**
	 * Get user's IP
	 * @return string|false
	 */
	function get_ip()
	{
		$get_ip = Ip::get();

		return $get_ip;
	}
}

if (! function_exists('check_ip')) {
	/**
	 * Validate IP
	 * @return string|false
	 */
	function check_ip($ip)
	{
		$check_ip = Ip::validate($ip);

		return $check_ip;
	}
}

// ------------------------------------------------------------------------

if (! function_exists('json_array_to_file')) {
	/**
	 * Creating JSON file from array
	 * @return boolean
	 */
	function json_array_to_file($array, $pathfile)
	{
		$json = Json::array_to_file($array, $pathfile);

		return $json;
	}
}

if (! function_exists('json_file_to_array')) {
	/**
	 * Create array from the JSON file content
	 * @return array|false
	 */
	function json_file_to_array($pathfile)
	{
		$json = Json::file_to_array($pathfile);

		return $json;
	}
}

if (! function_exists('json_last_error')) {
	/**
	 * Check for errors
	 * @return array|null
	 */
	function json_last_error()
	{
		$lastError = JsonLastError::check();

		if (!is_null($lastError)) {
		    return $lastError;
		}
	}
}

if (! function_exists('json_collection_error')) {
	/**
	 * Get collection of JSON errors
	 * @return array
	 */
	function json_collection_error()
	{
		$jsonLastErrorCollection = JsonLastError::get_collection();

		return $jsonLastErrorCollection;
	}
}

// ------------------------------------------------------------------------

if (! function_exists('parse_language')) {
	/**
	 * Get all language codes as array
	 * @return array
	 */
	function parse_language()
	{
		$arr = LanguageCode::get();

		return $arr;
	}
}

if (! function_exists('get_language_name')) {
	/**
	 * Get language name from language code
	 * @return string
	 */
	function get_language_name($langcode)
	{
		$langname = LanguageCode::get_language_from_code($langcode);

		return $langname;
	}
}

if (! function_exists('get_language_code')) {
	/**
	 * Get language code from language name
	 * @return string
	 */
	function get_language_code($langname)
	{
		$langcode = LanguageCode::get_code_from_language($langname);

		return $langcode;
	}
}

// ------------------------------------------------------------------------

if (! function_exists('validate_array')) {
	/**
	 * Parameter return as array
	 * @return array
	 */
	function validate_array($data, $default = null)
	{
		$result = Validate::as_array($data, $default = null);

		return $result;
	}
}

if (! function_exists('validate_object')) {
	/**
	 * Parameter return as object
	 * @return object
	 */
	function validate_object($data, $default = null)
	{
		$result = Validate::as_object($data, $default = null);

		return $result;
	}
}

if (! function_exists('validate_json')) {
	/**
	 * Parameter return as json
	 * @return string
	 */
	function validate_json($data, $default = null)
	{
		$result = Validate::as_json($data, $default = null);

		return $result;
	}
}

if (! function_exists('validate_string')) {
	/**
	 * Parameter return as string
	 * @return string
	 */
	function validate_string($data, $default = null)
	{
		$result = Validate::as_string($data, $default = null);

		return $result;
	}
}

if (! function_exists('validate_integer')) {
	/**
	 * Parameter return as integer
	 * @return integer
	 */
	function validate_integer($data, $default = null)
	{
		$result = Validate::as_integer($data, $default = null);

		return $result;
	}
}

if (! function_exists('validate_float')) {
	/**
	 * Parameter return as float
	 * @return float
	 */
	function validate_float($data, $default = null)
	{
		$result = Validate::as_float($data, $default = null);

		return $result;
	}
}

if (! function_exists('validate_boolean')) {
	/**
	 * Parameter return as boolean
	 * @return boolean
	 */
	function validate_boolean($data, $default = null)
	{
		$result = Validate::as_boolean($data, $default = null);

		return $result;
	}
}

if (! function_exists('validate_ip')) {
	/**
	 * Parameter return as ip
	 * @return string
	 */
	function validate_ip($data, $default = null)
	{
		$result = Validate::as_ip($data, $default = null);

		return $result;
	}
}

if (! function_exists('validate_url')) {
	/**
	 * Parameter return as url
	 * @return string
	 */
	function validate_url($data, $default = null)
	{
		$result = Validate::as_url($data, $default = null);

		return $result;
	}
}

if (! function_exists('validate_email')) {
	/**
	 * Parameter return as email
	 * @return string
	 */
	function validate_email($data, $default = null)
	{
		$result = Validate::as_email($data, $default = null);

		return $result;
	}
}

/**
 * NSY PHP Framework
 *
 * A several CI helpers of NSY PHP Framework from Codeigniter (Start)
 */
/**
 * stringify_attributes function
 *
 * @return string
 */
function stringify_attributes($attributes, $js = false)
{
    if (is_object($attributes) && count($attributes) > 0) {
        $attributes = (array) $attributes;
    }

    if (is_array($attributes)) {
        $atts = '';
        if (count($attributes) === 0) {
            return $atts;
        }
        foreach ($attributes as $key => $val)
        {
            if ($js) {
                $atts .= $key.'='.$val.',';
            }
            else
            {
                $atts .= ' '.$key.'="'.$val.'"';
            }
        }
        return rtrim($atts, ',');
    }
    elseif (is_string($attributes) && strlen($attributes) > 0) {
        return ' '.$attributes;
    }

    return $attributes;
}

// ------------------------------------------------------------------------

if (! function_exists('set_realpath')) {
    /**
     * Set Realpath
     *
     * @param  string
     * @param  bool    checks to see if the path exists
     * @return string
     */
    function set_realpath($path, $check_existance = false)
    {
        // Security check to make sure the path is NOT a URL. No remote file inclusion!
        if (preg_match('#^(http:\/\/|https:\/\/|www\.|ftp|php:\/\/)#i', $path) OR filter_var($path, FILTER_VALIDATE_IP) === $path) {
            echo 'The path you submitted must be a local server path, not a URL';
            exit();
        }

        // Resolve the path
        if (realpath($path) !== false) {
            $path = realpath($path);
        }
        elseif ($check_existance && ! is_dir($path) && ! is_file($path)) {
            echo 'Not a valid path: '.$path;
            exit();
        }

        // Add a trailing slash, if this is a directory
        return is_dir($path) ? rtrim($path, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR : $path;
    }
}

// ------------------------------------------------------------------------

if (! function_exists('random_element')) {
    /**
     * Random Element - Takes an array as input and returns a random element
     *
     * @param  array
     * @return mixed    depends on what the array contains
     */
    function random_element($array)
    {
        return is_array($array) ? $array[array_rand($array)] : $array;
    }
}

// --------------------------------------------------------------------

if (! function_exists('directory_map')) {
    /**
     * Create a Directory Map
     *
     * Reads the specified directory and builds an array
     * representation of it. Sub-folders contained with the
     * directory will be mapped as well.
     *
     * @param  string $source_dir      Path to source
     * @param  int    $directory_depth Depth of directories to traverse
     * @param  bool   $hidden          Whether to show hidden files
     * @return array
     */
    function directory_map($source_dir, $directory_depth = 0, $hidden = false)
    {
        if ($fp = @opendir($source_dir)) {
            $filedata    = array();
            $new_depth    = $directory_depth - 1;
            $source_dir    = rtrim($source_dir, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

            while (false !== ($file = readdir($fp)))
            {
                // Remove '.', '..', and hidden files [optional]
                if ($file === '.' OR $file === '..' OR ($hidden === false && $file[0] === '.')) {
                    continue;
                }

                is_dir($source_dir.$file) && $file .= DIRECTORY_SEPARATOR;

                if (($directory_depth < 1 OR $new_depth > 0) && is_dir($source_dir.$file)) {
                    $filedata[$file] = directory_map($source_dir.$file, $new_depth, $hidden);
                }
                else
                {
                    $filedata[] = $file;
                }
            }

            closedir($fp);
            return $filedata;
        }

        return false;
    }
}

//--------------------------------------------------------------------

if (! function_exists('encode_php_tags')) {
    /**
     * Convert PHP tags to entities
     *
     * @param  string $str
     * @return string
     */
    function encode_php_tags(string $str): string
    {
        return str_replace(['<?', '?>'], ['&lt;?', '?&gt;'], $str);
    }
}

//--------------------------------------------------------------------

if (! function_exists('word_limiter')) {
    /**
     * Word Limiter
     *
     * Limits a string to X number of words.
     *
     * @param string  $str
     * @param integer $limit
     * @param string  $end_char the end character. Usually an ellipsis
     *
     * @return string
     */
    function word_limiter(string $str, int $limit = 100, string $end_char = '&#8230;'): string
    {
        if (trim($str) === '') {
            return $str;
        }

        preg_match('/^\s*+(?:\S++\s*+){1,' . (int) $limit . '}/', $str, $matches);

        if (strlen($str) === strlen($matches[0])) {
            $end_char = '';
        }

        return rtrim($matches[0]) . $end_char;
    }
}

//--------------------------------------------------------------------

if (! function_exists('character_limiter')) {
    /**
     * Character Limiter
     *
     * Limits the string based on the character count.  Preserves complete words
     * so the character count may not be exactly as specified.
     *
     * @param string  $str
     * @param integer $n
     * @param string  $end_char the end character. Usually an ellipsis
     *
     * @return string
     */
    function character_limiter(string $str, int $n = 500, string $end_char = '&#8230;'): string
    {
        if (mb_strlen($str) < $n) {
            return $str;
        }

        // a bit complicated, but faster than preg_replace with \s+
        $str = preg_replace('/ {2,}/', ' ', str_replace(["\r", "\n", "\t", "\x0B", "\x0C"], ' ', $str));

        if (mb_strlen($str) <= $n) {
            return $str;
        }

        $out = '';

        foreach (explode(' ', trim($str)) as $val)
        {
            $out .= $val . ' ';
            if (mb_strlen($out) >= $n) {
                $out = trim($out);
                break;
            }
        }
        return (mb_strlen($out) === mb_strlen($str)) ? $out : $out . $end_char;
    }
}

//--------------------------------------------------------------------

if (! function_exists('ascii_to_entities')) {
    /**
     * High ASCII to Entities
     *
     * Converts high ASCII text and MS Word special characters to character entities.
     *
     * @param string $str
     *
     * @return string
     */
    function ascii_to_entities(string $str): string
    {
        $out = '';

        for ($i = 0, $s = strlen($str) - 1, $count = 1, $temp = []; $i <= $s; $i ++)
        {
            $ordinal = ord($str[$i]);

            if ($ordinal < 128) {
                /*
                  If the $temp array has a value but we have moved on, then it seems only
                  fair that we output that entity and restart $temp before continuing.
                 */
                if (count($temp) === 1) {
                    $out  .= '&#' . array_shift($temp) . ';';
                    $count = 1;
                }

                $out .= $str[$i];
            }
            else
            {
                if (empty($temp)) {
                    $count = ($ordinal < 224) ? 2 : 3;
                }

                $temp[] = $ordinal;

                if (count($temp) === $count) {
                    $number = ($count === 3) ? (($temp[0] % 16) * 4096) + (($temp[1] % 64) * 64) + ($temp[2] % 64) : (($temp[0] % 32) * 64) + ($temp[1] % 64);
                    $out   .= '&#' . $number . ';';
                    $count  = 1;
                    $temp   = [];
                }
                // If this is the last iteration, just output whatever we have
                elseif ($i === $s) {
                    $out .= '&#' . implode(';', $temp) . ';';
                }
            }
        }

        return $out;
    }
}

//--------------------------------------------------------------------

if (! function_exists('entities_to_ascii')) {
    /**
     * Entities to ASCII
     *
     * Converts character entities back to ASCII.
     *
     * @param string  $str
     * @param boolean $all
     *
     * @return string
     */
    function entities_to_ascii(string $str, bool $all = true): string
    {
        if (preg_match_all('/\&#(\d+)\;/', $str, $matches)) {
            for ($i = 0, $s = count($matches[0]); $i < $s; $i ++)
            {
                $digits = $matches[1][$i];
                $out    = '';
                if ($digits < 128) {
                    $out .= chr($digits);
                }
                elseif ($digits < 2048) {
                    $out .= chr(192 + (($digits - ($digits % 64)) / 64)) . chr(128 + ($digits % 64));
                }
                else
                {
                    $out .= chr(224 + (($digits - ($digits % 4096)) / 4096))
                    . chr(128 + ((($digits % 4096) - ($digits % 64)) / 64))
                    . chr(128 + ($digits % 64));
                }
                $str = str_replace($matches[0][$i], $out, $str);
            }
        }

        if ($all) {
            return str_replace(
                [
                '&amp;',
                '&lt;',
                '&gt;',
                '&quot;',
                '&apos;',
                '&#45;',
                ], [
                '&',
                '<',
                '>',
                '"',
                "'",
                '-',
                ], $str
            );
        }

        return $str;
    }
}

//--------------------------------------------------------------------

if (! function_exists('word_censor')) {
    /**
     * Word Censoring Function
     *
     * Supply a string and an array of disallowed words and any
     * matched words will be converted to #### or to the replacement
     * word you've submitted.
     *
     * @param string $str         the text string
     * @param array  $censored    the array of censored words
     * @param string $replacement the optional replacement value
     *
     * @return string
     */
    function word_censor(string $str, array $censored, string $replacement = ''): string
    {
        if (empty($censored)) {
            return $str;
        }

        $str = ' ' . $str . ' ';

        // \w, \b and a few others do not match on a unicode character
        // set for performance reasons. As a result words like über
        // will not match on a word boundary. Instead, we'll assume that
        // a bad word will be bookended by any of these characters.
        $delim = '[-_\'\"`(){}<>\[\]|!?@#%&,.:;^~*+=\/ 0-9\n\r\t]';

        foreach ($censored as $badword)
        {
            $badword = str_replace('\*', '\w*?', preg_quote($badword, '/'));

            if ($replacement !== '') {
                $str = preg_replace(
                    "/({$delim})(" . $badword . ")({$delim})/i", "\\1{$replacement}\\3", $str
                );
            }
            elseif (preg_match_all("/{$delim}(" . $badword . "){$delim}/i", $str, $matches, PREG_PATTERN_ORDER | PREG_OFFSET_CAPTURE)) {
                $matches = $matches[1];

                for ($i = count($matches) - 1; $i >= 0; $i --)
                {
                    $length = strlen($matches[$i][0]);
                    $str    = substr_replace(
                        $str, str_repeat('#', $length), $matches[$i][1], $length
                    );
                }
            }
        }

        return trim($str);
    }
}

//--------------------------------------------------------------------

if (! function_exists('highlight_code')) {
    /**
     * Code Highlighter
     *
     * Colorizes code strings.
     *
     * @param string $str the text string
     *
     * @return string
     */
    function highlight_code(string $str): string
    {
        /* The highlight string function encodes and highlights
        * brackets so we need them to start raw.
        *
        * Also replace any existing PHP tags to temporary markers
        * so they don't accidentally break the string out of PHP,
        * and thus, thwart the highlighting.
        */
        $str = str_replace(
            [
            '&lt;',
            '&gt;',
            '<?',
            '?>',
            '<%',
            '%>',
            '\\',
            '</script>',
            ], [
            '<',
            '>',
            'phptagopen',
            'phptagclose',
            'asptagopen',
            'asptagclose',
            'backslashtmp',
            'scriptclose',
            ], $str
        );

        // The highlight_string function requires that the text be surrounded
        // by PHP tags, which we will remove later
        $str = highlight_string('<?php ' . $str . ' ?>', true);

        // Remove our artificially added PHP, and the syntax highlighting that came with it
        $str = preg_replace(
            [
            '/<span style="color: #([A-Z0-9]+)">&lt;\?php(&nbsp;| )/i',
            '/(<span style="color: #[A-Z0-9]+">.*?)\?&gt;<\/span>\n<\/span>\n<\/code>/is',
            '/<span style="color: #[A-Z0-9]+"\><\/span>/i',
            ], [
            '<span style="color: #$1">',
            "$1</span>\n</span>\n</code>",
            '',
            ], $str
        );

        // Replace our markers back to PHP tags.
        return str_replace(
            [
            'phptagopen',
            'phptagclose',
            'asptagopen',
            'asptagclose',
            'backslashtmp',
            'scriptclose',
            ], [
            '&lt;?',
            '?&gt;',
            '&lt;%',
            '%&gt;',
            '\\',
            '&lt;/script&gt;',
            ], $str
        );
    }
}

//--------------------------------------------------------------------

if (! function_exists('highlight_phrase')) {
    /**
     * Phrase Highlighter
     *
     * Highlights a phrase within a text string.
     *
     * @param string $str       the text string
     * @param string $phrase    the phrase you'd like to highlight
     * @param string $tag_open  the opening tag to precede the phrase with
     * @param string $tag_close the closing tag to end the phrase with
     *
     * @return string
     */
    function highlight_phrase(string $str, string $phrase, string $tag_open = '<mark>', string $tag_close = '</mark>'): string
    {
        return ($str !== '' && $phrase !== '') ? preg_replace('/(' . preg_quote($phrase, '/') . ')/i', $tag_open . '\\1' . $tag_close, $str) : $str;
    }
}

//--------------------------------------------------------------------

if (! function_exists('word_wrap')) {
    /**
     * Word Wrap
     *
     * Wraps text at the specified character. Maintains the integrity of words.
     * Anything placed between {unwrap}{/unwrap} will not be word wrapped, nor
     * will URLs.
     *
     * @param string  $str     the text string
     * @param integer $charlim = 76    the number of characters to wrap at
     *
     * @return string
     */
    function word_wrap(string $str, int $charlim = 76): string
    {
        // Set the character limit
        is_numeric($charlim) || $charlim = 76;

        // Reduce multiple spaces
        $str = preg_replace('| +|', ' ', $str);

        // Standardize newlines
        if (strpos($str, "\r") !== false) {
            $str = str_replace(["\r\n", "\r"], "\n", $str);
        }

        // If the current word is surrounded by {unwrap} tags we'll
        // strip the entire chunk and replace it with a marker.
        $unwrap = [];

        if (preg_match_all('|\{unwrap\}(.+?)\{/unwrap\}|s', $str, $matches)) {
            for ($i = 0, $c = count($matches[0]); $i < $c; $i ++)
            {
                $unwrap[] = $matches[1][$i];
                $str      = str_replace($matches[0][$i], '{{unwrapped' . $i . '}}', $str);
            }
        }

        // Use PHP's native function to do the initial wordwrap.
        // We set the cut flag to FALSE so that any individual words that are
        // too long get left alone. In the next step we'll deal with them.
        $str = wordwrap($str, $charlim, "\n", false);

        // Split the string into individual lines of text and cycle through them
        $output = '';

        foreach (explode("\n", $str) as $line)
        {
            // Is the line within the allowed character count?
            // If so we'll join it to the output and continue
            if (mb_strlen($line) <= $charlim) {
                $output .= $line . "\n";
                continue;
            }

            $temp = '';

            while (mb_strlen($line) > $charlim)
            {
                // If the over-length word is a URL we won't wrap it
                if (preg_match('!\[url.+\]|://|www\.!', $line)) {
                    break;
                }
                // Trim the word down
                $temp .= mb_substr($line, 0, $charlim - 1);
                $line  = mb_substr($line, $charlim - 1);
            }

            // If $temp contains data it means we had to split up an over-length
            // word into smaller chunks so we'll add it back to our current line
            if ($temp !== '') {
                $output .= $temp . "\n" . $line . "\n";
            }
            else
            {
                $output .= $line . "\n";
            }
        }

        // Put our markers back
        if (! empty($unwrap)) {
            foreach ($unwrap as $key => $val)
            {
                $output = str_replace('{{unwrapped' . $key . '}}', $val, $output);
            }
        }

        // remove any trailing newline
        $output = rtrim($output);

        return $output;
    }
}

//--------------------------------------------------------------------

if (! function_exists('ellipsize')) {
    /**
     * Ellipsize String
     *
     * This function will strip tags from a string, split it at its max_length and ellipsize.
     *
     * @param string  $str        String to ellipsize
     * @param integer $max_length Max length of string
     * @param mixed   $position   int (1|0) or float, .5, .2, etc for position to split
     * @param string  $ellipsis   ellipsis ; Default '...'
     *
     * @return string    Ellipsized string
     */
    function ellipsize(string $str, int $max_length, $position = 1, string $ellipsis = '&hellip;'): string
    {
        // Strip tags
        $str = trim(strip_tags($str));

        // Is the string long enough to ellipsize?
        if (mb_strlen($str) <= $max_length) {
            return $str;
        }

        $beg      = mb_substr($str, 0, floor($max_length * $position));
        $position = ($position > 1) ? 1 : $position;

        if ($position === 1) {
            $end = mb_substr($str, 0, -($max_length - mb_strlen($beg)));
        }
        else
        {
            $end = mb_substr($str, -($max_length - mb_strlen($beg)));
        }

        return $beg . $ellipsis . $end;
    }
}

//--------------------------------------------------------------------

if (! function_exists('strip_slashes')) {
    /**
     * Strip Slashes
     *
     * Removes slashes contained in a string or in an array.
     *
     * @param mixed $str string or array
     *
     * @return mixed  string or array
     */
    function strip_slashes($str)
    {
        if (! is_array($str)) {
            return stripslashes($str);
        }
        foreach ($str as $key => $val)
        {
            $str[$key] = strip_slashes($val);
        }

        return $str;
    }
}

//--------------------------------------------------------------------

if (! function_exists('strip_quotes')) {
    /**
     * Strip Quotes
     *
     * Removes single and double quotes from a string.
     *
     * @param string $str
     *
     * @return string
     */
    function strip_quotes(string $str): string
    {
        return str_replace(['"', "'"], '', $str);
    }
}

//--------------------------------------------------------------------

if (! function_exists('quotes_to_entities')) {
    /**
     * Quotes to Entities
     *
     * Converts single and double quotes to entities.
     *
     * @param string $str
     *
     * @return string
     */
    function quotes_to_entities(string $str): string
    {
        return str_replace(["\'", '"', "'", '"'], ['&#39;', '&quot;', '&#39;', '&quot;'], $str);
    }
}

//--------------------------------------------------------------------

if (! function_exists('reduce_double_slashes')) {
    /**
     * Reduce Double Slashes
     *
     * Converts double slashes in a string to a single slash,
     * except those found in http://
     *
     * http://www.some-site.com//index.php
     *
     * becomes:
     *
     * http://www.some-site.com/index.php
     *
     * @param string $str
     *
     * @return string
     */
    function reduce_double_slashes(string $str): string
    {
        return preg_replace('#(^|[^:])//+#', '\\1/', $str);
    }
}

//--------------------------------------------------------------------

if (! function_exists('reduce_multiples')) {
    /**
     * Reduce Multiples
     *
     * Reduces multiple instances of a particular character.  Example:
     *
     * Fred, Bill,, Joe, Jimmy
     *
     * becomes:
     *
     * Fred, Bill, Joe, Jimmy
     *
     * @param string  $str
     * @param string  $character the character you wish to reduce
     * @param boolean $trim      TRUE/FALSE - whether to trim the character from the beginning/end
     *
     * @return string
     */
    function reduce_multiples(string $str, string $character = ',', bool $trim = false): string
    {
        $str = preg_replace('#' . preg_quote($character, '#') . '{2,}#', $character, $str);

        return ($trim) ? trim($str, $character) : $str;
    }
}

//--------------------------------------------------------------------

if (! function_exists('random_string')) {
    /**
     * Create a Random String
     *
     * Useful for generating passwords or hashes.
     *
     * @param string  $type Type of random string.  basic, alpha, alnum, numeric, nozero, md5, sha1, and crypto
     * @param integer $len  Number of characters
     *
     * @return string
     */
    function random_string(string $type = 'alnum', int $len = 8): string
    {
        switch ($type)
        {
        case 'alnum':
        case 'numeric':
        case 'nozero':
        case 'alpha':
            switch ($type)
            {
            case 'alpha':
                $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case 'alnum':
                $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case 'numeric':
                $pool = '0123456789';
                break;
            case 'nozero':
                $pool = '123456789';
                break;
            }

            return substr(str_shuffle(str_repeat($pool, ceil($len / strlen($pool)))), 0, $len);
        case 'md5':
            return md5(uniqid(mt_rand(), true));
        case 'sha1':
            return sha1(uniqid(mt_rand(), true));
        case 'crypto':
            return bin2hex(random_bytes($len / 2));
        }
        // 'basic' type treated as default
        return (string) mt_rand();
    }
}

//--------------------------------------------------------------------

if (! function_exists('increment_string')) {
    /**
     * Add's _1 to a string or increment the ending number to allow _2, _3, etc
     *
     * @param string  $str       Required
     * @param string  $separator What should the duplicate number be appended with
     * @param integer $first     Which number should be used for the first dupe increment
     *
     * @return string
     */
    function increment_string(string $str, string $separator = '_', int $first = 1): string
    {
        preg_match('/(.+)' . preg_quote($separator, '/') . '([0-9]+)$/', $str, $match);

        return isset($match[2]) ? $match[1] . $separator . ($match[2] + 1) : $str . $separator . $first;
    }
}

//--------------------------------------------------------------------

if (! function_exists('alternator')) {
    /**
     * Alternator
     *
     * Allows strings to be alternated. See docs...
     *
     * @param string (as many parameters as needed)
     *
     * @return string
     */
    function alternator(): string
    {
        static $i;

        if (func_num_args() === 0) {
            $i = 0;

            return '';
        }

        $args = func_get_args();

        return $args[($i++ % count($args))];
    }
}

//--------------------------------------------------------------------

if (! function_exists('excerpt')) {
    /**
     * Excerpt.
     *
     * Allows to extract a piece of text surrounding a word or phrase.
     *
     * @param string  $text     String to search the phrase
     * @param string  $phrase   Phrase that will be searched for.
     * @param integer $radius   The amount of characters returned around the phrase.
     * @param string  $ellipsis Ending that will be appended
     *
     * @return string
     *
     * If no $phrase is passed, will generate an excerpt of $radius characters
     * from the beginning of $text.
     */
    function excerpt(string $text, string $phrase = null, int $radius = 100, string $ellipsis = '...'): string
    {
        if (isset($phrase)) {
            $phrasePos = strpos(strtolower($text), strtolower($phrase));
            $phraseLen = strlen($phrase);
        }
        elseif (! isset($phrase)) {
            $phrasePos = $radius / 2;
            $phraseLen = 1;
        }

        $pre = explode(' ', substr($text, 0, $phrasePos));
        $pos = explode(' ', substr($text, $phrasePos + $phraseLen));

        $prev  = ' ';
        $post  = ' ';
        $count = 0;

        foreach (array_reverse($pre) as $pr => $e)
        {
            if ((strlen($e) + $count + 1) < $radius) {
                $prev = ' ' . $e . $prev;
            }
            $count = ++ $count + strlen($e);
        }

        $count = 0;

        foreach ($pos as $po => $s)
        {
            if ((strlen($s) + $count + 1) < $radius) {
                $post .= $s . ' ';
            }
            $count = ++ $count + strlen($s);
        }

        $ellPre = $phrase ? $ellipsis : '';

        return str_replace('  ', ' ', $ellPre . $prev . $phrase . $post . $ellipsis);
    }
}

// ------------------------------------------------------------------------

if (! function_exists('prep_url')) {
    /**
     * Prep URL - Simply adds the http:// part if no scheme is included.
     *
     * Formerly used URI, but that does not play nicely with URIs missing
     * the scheme.
     *
     * @param  string $str the URL
     * @return string
     */
    function prep_url(string $str = ''): string
    {
        if ($str === 'http://' || $str === '') {
            return '';
        }

        $url = parse_url($str);

        if (! $url || ! isset($url['scheme'])) {
            return 'http://' . $str;
        }

        return $str;
    }
}

// ------------------------------------------------------------------------

if (! function_exists('url_title')) {
    /**
     * Create URL Title
     *
     * Takes a "title" string as input and creates a
     * human-friendly URL string with a "separator" string
     * as the word separator.
     *
     * @param  string  $str       Input string
     * @param  string  $separator Word separator (usually '-' or '_')
     * @param  boolean $lowercase Whether to transform the output string to lowercase
     * @return string
     */
    function url_title(string $str, string $separator = '-', bool $lowercase = false): string
    {
        $q_separator = preg_quote($separator, '#');

        $trans = [
        '&.+?;'                   => '',
        '[^\w\d _-]'              => '',
        '\s+'                     => $separator,
        '(' . $q_separator . ')+' => $separator,
        ];

        $str = strip_tags($str);
        foreach ($trans as $key => $val)
        {
            //            $str = preg_replace('#'.$key.'#i'.( UTF8_ENABLED ? 'u' : ''), $val, $str);
            $str = preg_replace('#' . $key . '#iu', $val, $str);
        }

        if ($lowercase === true) {
            $str = mb_strtolower($str);
        }

        return trim(trim($str, $separator));
    }
}

// ------------------------------------------------------------------------

if (! function_exists('xml_convert')) {
    /**
     * Convert Reserved XML characters to Entities
     *
     * @param  string
     * @param  bool
     * @return string
     */
    function xml_convert($str, $protect_all = false)
    {
        $temp = '__TEMP_AMPERSANDS__';

        // Replace entities to temporary markers so that
        // ampersands won't get messed up
        $str = preg_replace('/&#(\d+);/', $temp.'\\1;', $str);

        if ($protect_all === true) {
            $str = preg_replace('/&(\w+);/', $temp.'\\1;', $str);
        }

        $str = str_replace(
            array('&', '<', '>', '"', "'", '-'),
            array('&amp;', '&lt;', '&gt;', '&quot;', '&apos;', '&#45;'),
            $str
        );

        // Decode the temp markers back to entities
        $str = preg_replace('/'.$temp.'(\d+);/', '&#\\1;', $str);

        if ($protect_all === true) {
            return preg_replace('/'.$temp.'(\w+);/', '&\\1;', $str);
        }

        return $str;
    }
}

// --------------------------------------------------------------------

if (! function_exists('symbolic_permissions')) {
    /**
     * Symbolic Permissions
     *
     * Takes a numeric value representing a file's permissions and returns
     * standard symbolic notation representing that value
     *
     * @param  int $perms Permissions
     * @return string
     */
    function symbolic_permissions($perms)
    {
        if (($perms & 0xC000) === 0xC000) {
            $symbolic = 's'; // Socket
        }
        elseif (($perms & 0xA000) === 0xA000) {
            $symbolic = 'l'; // Symbolic Link
        }
        elseif (($perms & 0x8000) === 0x8000) {
            $symbolic = '-'; // Regular
        }
        elseif (($perms & 0x6000) === 0x6000) {
            $symbolic = 'b'; // Block special
        }
        elseif (($perms & 0x4000) === 0x4000) {
            $symbolic = 'd'; // Directory
        }
        elseif (($perms & 0x2000) === 0x2000) {
            $symbolic = 'c'; // Character special
        }
        elseif (($perms & 0x1000) === 0x1000) {
            $symbolic = 'p'; // FIFO pipe
        }
        else
        {
            $symbolic = 'u'; // Unknown
        }

        // Owner
        $symbolic .= (($perms & 0x0100) ? 'r' : '-')
        .(($perms & 0x0080) ? 'w' : '-')
        .(($perms & 0x0040) ? (($perms & 0x0800) ? 's' : 'x' ) : (($perms & 0x0800) ? 'S' : '-'));

        // Group
        $symbolic .= (($perms & 0x0020) ? 'r' : '-')
        .(($perms & 0x0010) ? 'w' : '-')
        .(($perms & 0x0008) ? (($perms & 0x0400) ? 's' : 'x' ) : (($perms & 0x0400) ? 'S' : '-'));

        // World
        $symbolic .= (($perms & 0x0004) ? 'r' : '-')
        .(($perms & 0x0002) ? 'w' : '-')
        .(($perms & 0x0001) ? (($perms & 0x0200) ? 't' : 'x' ) : (($perms & 0x0200) ? 'T' : '-'));

        return $symbolic;
    }
}

// --------------------------------------------------------------------

if (! function_exists('octal_permissions')) {
    /**
     * Octal Permissions
     *
     * Takes a numeric value representing a file's permissions and returns
     * a three character string representing the file's octal permissions
     *
     * @param  int $perms Permissions
     * @return string
     */
    function octal_permissions($perms)
    {
        return substr(sprintf('%o', $perms), -3);
    }
}
/**
 * NSY PHP Framework
 *
 * A several CI helpers of NSY PHP Framework from Codeigniter (End)
 */
