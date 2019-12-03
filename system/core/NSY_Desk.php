<?php
namespace Core;

defined('ROOT') OR exit('No direct script access allowed');

/*
 * This is the core of NSY System Settings
 * 2018 - Vikry Yuansah
 * Attention, don't try to change the structure of the code, delete, or change. Because there is some code connected to the NSY system. So, be careful.
 */
class NSY_Desk {

	/**
	 * Function error handler
	 *
	 * @return string
	 */
	public function error_handler($var_msg = null) {
		if (defined('ENVIRONMENT')) {
			switch (ENVIRONMENT) {
				// Set as under development
				case 'development':
					try {
						throw new \Exception($var_msg);
					}
					catch (\Exception $e) {
						$err = $e->getTrace();
						// echo '<pre>', print_r($e->getTrace()), '</pre>';
						// echo '<pre>', $e->__toString(), '</pre>';
						echo "<pre>Message:\n" . $e->getMessage() ." in ".  $err[1]['file'] . "(". $err[1]['line'] .')' ."</pre>";
						echo "<pre>Stack trace:\n#0 ". $err[1]['file'] . "(" . $err[1]['line'] . "): " . $err[2]['class'] . "->" . $err[2]['function'] . "()" ."</pre>";
					}
				break;
				default:
			}
		}
	}

	public static function static_error_handler($var_msg = null) {
		if (defined('ENVIRONMENT')) {
			switch (ENVIRONMENT) {
				// Set as under development
				case 'development':
					try {
						throw new \Exception($var_msg);
					}
					catch (\Exception $e) {
						$err = $e->getTrace();
						// echo '<pre>', print_r($e->getTrace()), '</pre>';
						// echo '<pre>', $e->__toString(), '</pre>';
						echo "<pre>Message:\n" . $e->getMessage() ." in ".  $err[1]['file'] . "(". $err[1]['line'] .')' ."</pre>";
						echo "<pre>Stack trace:\n#0 ". $err[1]['file'] . "(" . $err[1]['line'] . "): " . $err[2]['class'] . "->" . $err[2]['function'] . "()" ."</pre>";
					}
				break;
				default:
			}
		}
	}

	/**
	 * Function error swith
	 *
	 */
	public function error_switch() {
		if (defined('ENVIRONMENT')) {
			switch (ENVIRONMENT) {
				// Set as under development
				case 'development':
					ini_set('display_errors', 1);
					ini_set('display_startup_errors', 1);
					error_reporting(E_ALL);
				break;

				// Set as under production/go live
				case 'production':
					ini_set('display_errors', 0);
					error_reporting(0);

					if (version_compare(PHP_VERSION, '5.3', '>=')) {
						error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
					} else {
						error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
					}
				break;
				default:

				header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
				exit('The application environment is not set correctly.');
				exit(1); // EXIT_ERROR
			}
		}
	}

}
