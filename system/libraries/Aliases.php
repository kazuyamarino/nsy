<?php
$arr = config_app('aliases');

if ( is_array($arr) ) {
	foreach( $arr as $key => $value ) {
		class_alias($value, $key);
	};
} else {
	$var_msg = 'The variable in the <mark>aliases</mark> <strong>config/App.php</strong> is improper or not an array';
	NSY_Desk::static_error_handler($var_msg);
	exit();
}
