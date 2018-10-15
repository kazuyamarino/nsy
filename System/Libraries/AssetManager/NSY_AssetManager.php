<?php

namespace System\Libraries\AssetManager;

defined('ROOT') OR exit('No direct script access allowed');

Class NSY_AssetManager {

	// generate css tags
	function link_css($filename, $rel, $type) {
		echo '<link rel="'.$rel.'" href="'.CSS_DIR.$filename.'.css" type="'.$type.'">';
	}

	// generate icon tags
	function link_icon($filename, $rel) {
		echo '<link rel="'.$rel.'" href="'.IMG_DIR.$filename.'">';
	}

	// generate url tags
	function link_url($url, $rel, $type) {
		echo '<link rel="'.$rel.'" href="'.$url.'" type="'.$type.'">';
	}

	// generate meta tags
	function meta($attr, $content) {
		// if content is empty
		if (empty($content) || is_null($content)) {
			// then show meta tags without content
			echo '<meta '.$attr.'>';
		} else {
			// then show meta tags with content
			echo '<meta '.$attr.' content="'.$content.'">';
		}
	}

	// generate custom assets
	function custom($values) {
		echo $values;
	}

	// generate script tags filename
	function script($filename, $type, $charset, $attr) {
		// if charset is empty
		if (empty($charset) || is_null($charset)) {
			// then show script tags without charset
			echo '<script src="'.JS_DIR.$filename.'.js" type="'.$type.'" '.$attr.'></script>';
		} else {
			// then show script tags with charset
			echo '<script src="'.JS_DIR.$filename.'.js" type="'.$type.'" charset="'.$charset.'" '.$attr.'></script>';
		}
	}

	// generate script tags url
	function script_url($filename, $type, $charset, $attr) {
		// if charset is empty
		if (empty($charset) || is_null($charset)) {
			// then show script tags without charset
			echo '<script src="'.$filename.'" type="'.$type.'" '.$attr.'></script>';
		} else {
			// then show script tags with charset
			echo '<script src="'.$filename.'" type="'.$type.'" charset="'.$charset.'" '.$attr.'></script>';
		}
	}

}
