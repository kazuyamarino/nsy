<?php
/**
* Use NSY_Desk class
*/
use System\Core\NSY_Desk;

/**
* Use NSY_CSRF class
*/
use System\Core\NSY_CSRF;

/**
* Use HtmlSanitizer class
*/
use HtmlSanitizer\Sanitizer;

/**
* Use AntiXSS class
*/
use voku\helper\AntiXSS;

/**
 * Security Helpers
 * @var mixed
 */
if (! function_exists('secure_input')) {
	/**
	* Secure Input Element
	* @param  string $data
	* @return string
	*/
	function secure_input($data = '')
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
	function secure_form($form = '')
	{
		if (is_array($form) || is_object($form)) {
			foreach ($form as $key => $value) {
				$form[$key] = secure_input($value);
			}
		}
	}
}

if (! function_exists('csrf_token')) {
	/**
	* Return only CSRF Token
	* @param string $var
	* @return string
	*/
	function csrf_token($var)
	{
		if(config_app('csrf_token') === 'true') {
			$csrf = new NSY_CSRF;
			$csrf_token = $csrf->generate($var);

			return $csrf_token;
		} elseif(config_app('csrf_token') === 'false') {
			$var_msg = "CSRF Token Protection must be set <strong><i>true</i></strong></p><p>See <strong>System/Config/App.php</strong>";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}
	}
}

if (! function_exists('csrf_token_form')) {
	/**
	* Return CSRF Input form with Token
	* @param string $var
	* @return string
	*/
	function csrf_token_form($var)
	{
		if(config_app('csrf_token') === 'true') {
			$csrf = new NSY_CSRF;
			$csrf_token = $csrf->generate($var);

			return '<input type="hidden" name="'.$var.'" value="' . $csrf_token . '">';
		} elseif(config_app('csrf_token') === 'false') {
			$var_msg = "CSRF Token Protection must be set <strong><i>true</i></strong></p><p>See <strong>System/Config/App.php</strong>";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}
	}
}

if (! function_exists('csrf_check')) {
	/**
	* Return CSRF Input form with Token
	* @param string $name
	* @param string $method
	* @param string $exception
	* @param string $validity
	* @param string $onetime
	* @return string
	*/
	function csrf_check($name, $method, $exception, $validity, $onetime)
	{
		if(config_app('csrf_token') === 'true') {
			$csrf = new NSY_CSRF;
			$checked = $csrf->check($name, $method, $exception, $validity, $onetime);

			return $checked;
		} elseif(config_app('csrf_token') === 'false') {
			$var_msg = "CSRF Token Protection must be set <strong><i>true</i></strong></p><p>See <strong>System/Config/App.php</strong>";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}
	}
}

if (! function_exists('html_sanitizer')) {
	/**
     * Quickly create an already configured sanitizer using the default builder.
     *
     * @param array $config
     * @param string $untrustedHtml
     */
	function html_sanitizer(array $config, $untrustedHtml)
	{
		$sanitizer = Sanitizer::create($config);
		$cleanCode = $sanitizer->sanitize($untrustedHtml);

		return $cleanCode;
	}
}

if (! function_exists('anti_xss')) {
	/**
     * XSS Clean
     *
     * Sanitizes data so that "Cross Site Scripting" hacks can be
     * prevented. This method does a fair amount of work but
     * it is extremely thorough, designed to prevent even the
     * most obscure XSS attempts. But keep in mind that nothing
     * is ever 100% foolproof...
     *
     * Note: Should only be used to deal with data upon submission.
     * It's not something that should be used for general runtime processing.
     *
     * @see http://channel.bitflux.ch/wiki/XSS_Prevention
     *    Based in part on some code and ideas from Bitflux.
     * @see http://ha.ckers.org/xss.html
     *    To help develop this script I used this great list of
     *    vulnerabilities along with a few other hacks I've
     *    harvested from examining vulnerabilities in other programs.
     *
     * @param string|string[] $str
	 * input data e.g. string or array of strings
     *
     * @return string|string[]
     * @template TXssCleanInput
     * @phpstan-param TXssCleanInput $str
     * @phpstan-return TXssCleanInput
     */
	function anti_xss($harm_string)
	{
		$AntiXSS = new AntiXSS();
		$harmless_string = $AntiXSS->xss_clean($harm_string);

		return $harmless_string;
	}
}
