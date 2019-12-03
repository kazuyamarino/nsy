<?php
namespace Core;

defined('ROOT') OR exit('No direct script access allowed');

/**
 * Use NSY_Desk class
 */
use Core\NSY_Desk;

/*
 * This is the core of NSY Asset Manager
 * 2018 - Vikry Yuansah
 * Attention, don't try to change the structure of the code, delete, or change. Because there is some code connected to the NSY system. So, be careful.
 */
Class NSY_AssetManager extends NSY_Desk {

	// generate meta tags
	protected static function meta($attr = null, $content = null) {
		// if content is empty
		if (not_filled($content)) {
			// then show meta tags without content
			echo '<meta '.$attr.'>';
		} elseif (is_filled($attr) || is_filled($content)) {
			// then show meta tags with content
			echo '<meta '.$attr.' content="'.$content.'">';
		} else {
			$var_msg = 'Please check the format of <mark>add::meta</mark> tag';
			self::static_error_handler($var_msg);
			exit();
		}
	}

	// generate link tags
	protected static function link($filename = null, $rel = null, $type = null) {
		// if content is empty
		if (strpos($filename, 'http') !== false || strpos($filename, 'https') !== false) {
			echo '<link rel="'.$rel.'" href="'.$filename.'" type="'.$type.'">';
		} elseif ($rel == 'stylesheet') {
			echo '<link rel="'.$rel.'" href="'.CSS_DIR.$filename.'" type="'.$type.'">';
		} elseif ($rel == 'shortcut icon')  {
			echo '<link rel="'.$rel.'" href="'.IMG_DIR.$filename.'">';
		} else {
			$var_msg = 'Please check the format of <mark>add::link</mark> tag';
			self::static_error_handler($var_msg);
			exit();
		}
	}

	// generate script tags filename
	protected static function script($filename = null, $type = null, $charset = null, $attr = null) {
		// if charset is empty
		if (strpos($filename, 'http') !== false || strpos($filename, 'https') !== false) {
			// if charset is empty
			if (not_filled($charset)) {
				// then show script tags without charset
				echo '<script src="'.$filename.'" type="'.$type.'" '.$attr.'></script>';
			} else {
				// then show script tags with charset
				echo '<script src="'.$filename.'" type="'.$type.'" charset="'.$charset.'" '.$attr.'></script>';
			}
		} elseif (not_filled($charset)) {
			// then show script tags without charset
			echo '<script src="'.JS_DIR.$filename.'" type="'.$type.'" '.$attr.'></script>';
		} else {
			// then show script tags with charset
			echo '<script src="'.JS_DIR.$filename.'" type="'.$type.'" charset="'.$charset.'" '.$attr.'></script>';
		}
	}

	// generate custom assets
	protected static function custom($values = null) {
		if (not_filled($values)) {
			$var_msg = 'No value in <mark>add::custom()</mark> tag';
			self::static_error_handler($var_msg);
			exit();
		} else {
			echo $values;
		}
	}

}
