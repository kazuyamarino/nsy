<?php
/*
* This is the core of NSY Helpers.
* Attention, don't try to change the structure of the code, delete, or change.
* Because there is some code connected to the NSY system. So, be careful.
*/

/**
* Use NSY_Desk class
*/
use System\Core\NSY_Desk;

/**
 * Variable Checking Helpers
 */
if (! function_exists('not_filled')) {
	/**
	* Function for basic field validation (present and neither empty nor only white space
	* @param  mixed $str
	* @return mixed
	*/
	function not_filled($str = '')
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
	* @param  mixed $str
	* @return mixed
	*/
	function is_filled($str = '')
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

/**
 * URI Helpers
 * @var mixed
 */
 /**
 * Define base_url() method, get base url with default project directory
 * @param  string $url
 * @return string
 */
 function base_url($url = '')
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

if (! function_exists('public_path')) {
	/**
	* Define public_path() method, get the fullpath 'public' directory
	* @param  string $url
	* @return string
	*/
	function public_path($url = '')
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
	function img_url($url = '')
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
	function js_url($url = '')
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
	function css_url($url = '')
	{
		if ( is_filled($url) ) {
			return CSS_DIR . $url;
		} else {
			return CSS_DIR;
		}
	}
}

if (! function_exists('redirect')) {
	/**
	* Method for Redirect to specified URI
	* @param  string $url
	* @return void
	*/
	function redirect($url = '')
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

// -----------------------------------------------------------------------

/**
* Get config value from system/Config/app.php
* @param  string|int $d1
* @return array
*/
function config_app($d1 = '')
{
	$app = include __DIR__ . '/../Config/App.php';

	return $app[$d1];
}

/**
* Get config value from system/Config/env.php
* @param  string|int $d1
* @return array
*/
function config_env($d1 = '',$d2 = '')
{
	$app = include __DIR__ . '/../../env.php';
	if (not_filled($d2) ) {
		return $app[$d1];
	} else {
		return $app[$d1][$d2];
	}
}

/**
* Get config value from system/Config/site.php
* @param  string|int $d1
* @return array
*/
function config_site($d1 = '')
{
	$site = include __DIR__ . '/../Config/Site.php';

	return $site[$d1];
}

// ------------------------------------------------------------------------

if (! function_exists('terner')) {
	/**
	* PHP Shorthand If/Else Using Ternary Operators
	* @param  string|int $condition
	* @param  string|int $result_one
	* @param  string|int $result_two
	* @return string|int
	*/
	function terner($condition = '', $result_one = '', $result_two = '')
	{
		$result = ($condition ? $result_one : $result_two);

		return $result;
	}
}

// ------------------------------------------------------------------------

/**
 * Aurora Data Export
 */
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
	function aurora($ext = '', $name = '', $sep = '', $h = '', $d = '', $s = '')
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

/**
 * Get User Agent
 */
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

/**
 * Generate Random Number
 */
if (! function_exists('generate_num')) {
	/**
	* Create Random Number
	* @param  string  $prefix
	* @param  int $id_length
	* @param  int $num_length
	* @return int|string
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

/**
 * Get URI Segment
 */
if (! function_exists('get_uri_segment')) {
	/**
	* Get URI Segment
	* @param  int $key
	* @return string
	*/
	function get_uri_segment($key = '')
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
 * NSY System Const Helpers
 */
if (! function_exists('get_version')) {
	/**
	* Get application version
	* @return string
	*/
	function get_version()
	{
		return VERSION;
	}
}

if (! function_exists('get_codename')) {
	/**
	* Get application codename
	* @return string
	*/
	function get_codename()
	{
		return CODENAME;
	}
}

if (! function_exists('get_lang_code')) {
	/**
	* Get application language code
	* @return string
	*/
	function get_lang_code()
	{
		return LANGUAGE_CODE;
	}
}

if (! function_exists('get_og_prefix')) {
	/**
	* Get open graph prefix
	* @return string
	*/
	function get_og_prefix()
	{
		return OG_PREFIX;
	}
}

if (! function_exists('get_title')) {
	/**
	* Get site title
	* @return string
	*/
	function get_title()
	{
		return SITETITLE;
	}
}

if (! function_exists('get_desc')) {
	/**
	* Get site description
	* @return string
	*/
	function get_desc()
	{
		return SITEDESCRIPTION;
	}
}

if (! function_exists('get_keywords')) {
	/**
	* Get site keywords
	* @return string
	*/
	function get_keywords()
	{
		return SITEKEYWORDS;
	}
}

if (! function_exists('get_author')) {
	/**
	* Get site author
	* @return string
	*/
	function get_author()
	{
		return SITEAUTHOR;
	}
}

if (! function_exists('get_session_prefix')) {
	/**
	* Get session prefix
	* @return string
	*/
	function get_session_prefix()
	{
		return SESSION_PREFIX;
	}
}

if (! function_exists('get_site_email')) {
	/**
	* Get site email
	* @return string
	*/
	function get_site_email()
	{
		return SITEEMAIL;
	}
}

if (! function_exists('get_vendor_dir')) {
	/**
	* Get vendor directory
	* @return string
	*/
	function get_vendor_dir()
	{
		return VENDOR_DIR;
	}
}

if (! function_exists('get_mvc_view_dir')) {
	/**
	* Get MVC View directory
	* @return string
	*/
	function get_mvc_view_dir()
	{
		return MVC_VIEW_DIR;
	}
}

if (! function_exists('get_hmvc_view_dir')) {
	/**
	* Get HMVC View directory
	* @return string
	*/
	function get_hmvc_view_dir()
	{
		return HMVC_VIEW_DIR;
	}
}

if (! function_exists('get_system_dir')) {
	/**
	* Get HMVC View directory
	* @return string
	*/
	function get_system_dir()
	{
		return SYS_TMP_DIR;
	}
}

// ------------------------------------------------------------------------

if (! function_exists('string_encrypt')) {
	/**
	* Simple string encryption/decryption function.
	* CHANGE $secret_key and $secret_iv !!!
	* @param  string $action 'encrypt/decrypt'
	* @param  string $string
	* @return string
	*/
	function string_encrypt($action = 'encrypt', $string = '')
	{
		if ( is_filled($action) || is_filled($string) ) {
			$output = false;

			$encrypt_method = 'AES-256-CBC'; // Default
			$secret_key = 'Kazu#Key!'; // Change the key!
			$secret_iv = '!VI@_$3'; // Change the init vector!

			// hash
			$key = hash('sha256', $secret_key);

			// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
			$iv = substr(hash('sha256', $secret_iv), 0, 16);

			if( $action == 'encrypt' ) {
				$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
				$output = base64_encode($output);
			}
			else if( $action == 'decrypt' ){
				$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
			}

			return $output;
		} else {
			$var_msg = 'The variable <mark>string_encrypt(<strong>actions</strong>, <strong>string</strong>)</mark> is improper or not an array';
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}
	}
}

if (! function_exists('image_to_base64')) {
	/**
	* Convert image file to base64
	* @param  string $files
	* @return array
	*/
	function image_to_base64($files = '')
	{
		if ( is_filled($files) ) {
			$fileName = $files['name'];
			$fileType = $files['type'];
			$fileContent = file_get_contents($files['tmp_name']);
			$base64 = base64_encode($fileContent);
			$dataUrl = 'data:' . $fileType . ';base64,' . base64_encode($fileContent);

			$arr = array(
				'name' => $fileName,
				'type' => $fileType,
				'dataUrl' => $dataUrl,
				'base64' => $base64
			);

			return $arr;
		} else {
			$var_msg = 'The variable <mark>image_to_base64(<strong>variables</strong>)</mark> is improper or not an array';
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}
	}
}

if (! function_exists('string_to_base64')) {
	/**
	* Convert image string to base64
	* @param  string $files
	* @param  string $ext [File extension]
	* @return array
	*/
	function string_to_base64($files = '', $ext = 'jpg')
	{
		if ( is_filled($files) || is_filled($ext) ) {
			$base64 = base64_encode($files);
			$dataUrl = 'data:images/' . $ext . ';base64,' . $base64;

			$arr = array(
				'dataUrl' => $dataUrl,
				'base64' => $base64
			);

			return $arr;
		} else {
			$var_msg = 'The variable <mark>string_to_base64(<strong>variables</strong>)</mark> is improper or not an array';
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}
	}
}

// ------------------------------------------------------------------------

if (! function_exists('number_format_short')) {
	/**
	* @param $n
	* @return string
	* Use to convert large positive numbers in to short form like 1K+, 100K+, 199K+, 1M+, 10M+, 1B+ etc
	*/
	function number_format_short( $n, $precision = 1 )
	{
		if ($n < 900) {
			// 0 - 900
			$n_format = number_format($n, $precision);
			$suffix = '';
		} else if ($n < 900000) {
			// 0.9k-850k
			$n_format = number_format($n / 1000, $precision);
			$suffix = 'K';
		} else if ($n < 900000000) {
			// 0.9m-850m
			$n_format = number_format($n / 1000000, $precision);
			$suffix = 'M';
		} else if ($n < 900000000000) {
			// 0.9b-850b
			$n_format = number_format($n / 1000000000, $precision);
			$suffix = 'B';
		} else {
			// 0.9t+
			$n_format = number_format($n / 1000000000000, $precision);
			$suffix = 'T';
		}

		// Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
		// Intentionally does not affect partials, eg "1.50" -> "1.50"
		if ( $precision > 0 ) {
			$dotzero = '.' . str_repeat( '0', $precision );
			$n_format = str_replace( $dotzero, '', $n_format );
		}

		return $n_format . $suffix;
	}
}

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

if (! function_exists('sequence')) {
	/**
	* Helper for create a sequence of the named placeholders
	*
	* @param string $bind
	* @param string $variables
	* @return array
	*/
	function sequence($bind, $variables)
	{
		$in = '';
		if (is_array($variables) || is_object($variables) || is_filled($bind) ) {
			foreach ($variables as $i => $item)
			{
				$key = $bind.$i;
				$in .= $key.',';
				$in_params[$key] = $item; // collecting values into key-value array
			}
		} else
		{
			$var_msg = 'The variable in the <mark>sequence(<strong>bind</strong>, <strong>variables</strong>)</mark> is improper or not an array';
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}
		$in = rtrim($in, ','); // example = :id0,:id1,:id2

		return [$in, $in_params];
	}
}

/**
* The PHP superglobals $_GET and $_POST are used to collect form-data.
*/
/**
* Post method
* @param  mixed|int $param
* @return mixed|int
*/
function post($param = '')
{
	if ( is_filled($param) ) {
		$result = isset($_POST[$param]) ? $_POST[$param] : null;
	} else {
		$var_msg = 'The variable in the <mark>post(<strong>variable</strong>)</mark> is improper or not an array';
		NSY_Desk::static_error_handler($var_msg);
		exit();
	}

	return $result;
}

/**
* Get method
* @param  mixed $param
* @return mixed
*/
function get($param = '')
{
	if ( is_filled($param) ) {
		$result = isset($_GET[$param]) ? $_GET[$param] : null;
	} else {
		$var_msg = 'The variable in the <mark>get(<strong>variable</strong>)</mark> is improper or not an array';
		NSY_Desk::static_error_handler($var_msg);
		exit();
	}

	return $result;
}

/**
* File method
* @param  mixed $param
* @return mixed
*/
function deposer($param = '')
{
	if ( is_filled($param) ) {
		$result = isset($_FILE[$param]) ? $_FILE[$param] : null;
	} else {
		$var_msg = 'The variable in the <mark>get(<strong>variable</strong>)</mark> is improper or not an array';
		NSY_Desk::static_error_handler($var_msg);
		exit();
	}

	return $result;
}
