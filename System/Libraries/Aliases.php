<?php

/**
 * Optimized NSY Class Aliases System
 * Uses NSY_AliasManager for better performance and caching
 */

use System\Core\NSY_AliasManager;
use System\Core\NSY_Desk;

/**
 * Load all configured class aliases using optimized manager
 * Features: caching, validation, performance monitoring, lazy loading
 */
if (!NSY_AliasManager::loadAliases()) {
	// Fallback to legacy method if manager fails
	$arr = config_app('aliases');
	if (is_array($arr)) {
		foreach ($arr as $key => $value) {
			if (class_exists($value)) {
				class_alias($value, $key);
			}
		}
	} else {
		$var_msg = 'The variable <mark>aliases</mark> key in the <strong>config/App.php</strong> is improper or not an array';
		NSY_Desk::static_error_handler($var_msg);
		exit();
	}
}
