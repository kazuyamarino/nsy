<?php
namespace Core;

defined('ROOT') OR exit('No direct script access allowed');

/*
 * This is the core of NSY Asset Manager
 * 2018 - Vikry Yuansah
 * Attention, don't try to change the structure of the code, delete, or change. Because there is some code connected to the NSY system. So, be careful.
 */
Class NSY_AssetManager {

	// generate meta tags
	protected static function meta($attr = null, $content = null) {
		// if content is empty
		if (not_filled($content)) {
			// then show meta tags without content
			echo '<meta '.$attr.'>';
		} else {
			// then show meta tags with content
			echo '<meta '.$attr.' content="'.$content.'">';
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
			echo '<p>Please check the format of <link> tag</p>';
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
			echo '<p>No value in custom method</p>';
			exit();
		} else {
			echo $values;
		}
	}

}
