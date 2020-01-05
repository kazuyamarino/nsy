<?php
defined('ROOT') OR exit('No direct script access allowed');

/*
 * This is the core of NSY Helpers
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
 * Define public_path() method
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

/**
 * Define base_url() method
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
 */
function config_app($d1 = null)
{
    $app = include __DIR__ . '/../config/App.php';

    return $app[$d1];
}

// ------------------------------------------------------------------------

/**
 * Get config value from system/config/site.php
 */
function config_site($d1 = null)
{
    $site = include __DIR__ . '/../config/Site.php';

    return $site[$d1];
}

// ------------------------------------------------------------------------

if (! function_exists('redirect')) {
    /**
     * Redirect URL
     */
    function redirect($url = null)
    {
        header('location:'. base_url($url));
        exit();
    }
}

if (! function_exists('redirect_back')) {
    /**
     * Redirect Back URL
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
     * Fetching to json format
     *
     * @return string
     */
    function fetch_json($data = null)
    {
        $json_data = $data;
        $json_result = json_encode($json_data);

        return $json_result;
    }
}

// ------------------------------------------------------------------------

if (! function_exists('secure_input')) {
    /**
     * Secure Input Element
     *
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
     *
     * @return string
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
     * CSRF Token
     *
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
     * CSRF Form Token
     *
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
     *
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
     *
     * @return string
     */
    function allow_http()
    {
        $allow_http = new NSY_XSS_Filter();
        $func = $allow_http->allow_http();

        return $func;
    }
}

if (! function_exists('disallow_http')) {
    /**
     * Disallow http
     *
     * @return string
     */
    function disallow_http()
    {
        $disallow_http = new NSY_XSS_Filter();
        $func = $disallow_http->disallow_http();

        return $func;
    }
}

if (! function_exists('remove_get_parameters')) {
    /**
     * Remove url get parameter
     *
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
     */
    function ternary($condition = null, $result_one = null, $result_two = null)
    {
        $result = ($condition ? $result_one : $result_two);

        return $result;
    }
}

// ------------------------------------------------------------------------

/**
 * The PHP superglobals $_GET and $_POST are used to collect form-data.
 */
if (! function_exists('post')) {
    function post($param = null)
    {
        $result = isset($_POST[$param]) ? $_POST[$param] : null;

        return $result;
    }
}

if (! function_exists('get')) {
    function get($param = null)
    {
        $result = isset($_GET[$param]) ? $_GET[$param] : null;

        return $result;
    }
}

// ------------------------------------------------------------------------

if (! function_exists('not_filled')) {
    /**
     * Function for basic field validation (present and neither empty nor only white space
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
     * Export File
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

/**
 * User Agent
 *
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
if (! function_exists('get_ua')) {
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
 * PHP array_flatten() function. Convert a multi-dimensional array into a single-dimensional array.
 * https://gist.github.com/SeanCannon/6585889#gistcomment-2922278
 */
if (! function_exists('array_flatten')) {
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

/**
 * Create Random Number
 */
if (! function_exists('generate_num')) {
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

/**
 * Get application version
 */
function get_version()
{
    return VERSION;
}

/**
 * Get application codename
 */
function get_codename()
{
    return CODENAME;
}

/**
 * Get application language code
 */
function get_lang_code()
{
    return LANGUAGE_CODE;
}

/**
 * Get open graph prefix
 */
function get_og_prefix()
{
    return OG_PREFIX;
}

/**
 * Get site title
 */
function get_title()
{
    return SITETITLE;
}

/**
 * Get site description
 */
function get_desc()
{
    return SITEDESCRIPTION;
}

/**
 * Get site keywords
 */
function get_keywords()
{
    return SITEKEYWORDS;
}

/**
 * Get site author
 */
function get_author()
{
    return SITEAUTHOR;
}

/**
 * Get session prefix
 */
function get_session_prefix()
{
    return SESSION_PREFIX;
}

/**
 * Get site email
 */
function get_site_email()
{
    return SITEEMAIL;
}
