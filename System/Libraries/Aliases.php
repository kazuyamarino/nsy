<?php

/**
 * Use NSY_Desk class
 */

use System\Core\NSY_Desk;

/**
 * NSY Class Aliases
 * Attention, don't try to change the structure of the code, delete, or change.
 * Because there is some code connected to the NSY system. So, be careful.
 */
$arr = config_app('aliases');

if (is_array($arr)) {
	foreach ($arr as $key => $value) {
		class_alias($value, $key);
	};
} else {
	$var_msg = 'The variable <mark>aliases</mark> key in the <strong>config/App.php</strong> is improper or not an array';
	NSY_Desk::static_error_handler($var_msg);
	exit();
}
