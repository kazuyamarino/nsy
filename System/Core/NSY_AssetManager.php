<?php
namespace System\Core;

/**
* This is the core of NSY Asset Manager
* Attention, don't try to change the structure of the code, delete, or change.
* Because there is some code connected to the NSY system. So, be careful.
*/
Class NSY_AssetManager
{

	/**
	* Generate meta tags
	*
	* @param  string $attr
	* @param  string $content
	* @return string
	*/
	public static function meta($attr = '', $content = '')
	{
		// if content is empty
		if (not_filled($content)) {
			// then show meta tags without content
			echo '<meta '.$attr.'>';
		} elseif (is_filled($attr) || is_filled($content)) {
			// then show meta tags with content
			echo '<meta '.$attr.' content="'.$content.'">';
		} else {
			$var_msg = 'Please check the format of <mark>Add::meta</mark> tag';
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}
	}

	/**
	* Generate link tags
	*
	* @param  string $filename
	* @param  string $rel
	* @param  string $type
	* @param  string $title
	* @return string
	*/
	public static function link($filename = '', $rel = '', $type = '', $title = '')
	{
		// if content is empty
		if (strpos($filename, 'http') !== false || strpos($filename, 'https') !== false) {
			echo '<link rel="'.$rel.'" href="'.$filename.'" type="'.$type.'">';
		} elseif (is_filled($title)) {
			echo '<link rel="'.$rel.'" href="'.$filename.'" type="'.$type.'" title="'.$title.'">';
		} elseif ($rel == 'stylesheet') {
			echo '<link rel="'.$rel.'" href="'.CSS_DIR.$filename.'" type="'.$type.'">';
		} elseif ($rel == 'shortcut icon') {
			echo '<link rel="'.$rel.'" href="'.IMG_DIR.$filename.'">';
		} else {
			$var_msg = 'Please check the format of <mark>Add::link</mark> tag';
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}
	}

	/**
	* Generate script tags filename
	*
	* @param  string $filename
	* @param  string $type
	* @param  string $charset
	* @param  string $attr
	* @return string
	*/
	public static function script($filename = '', $type = '', $charset = '', $attr = '')
	{
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

	/**
	* Generate custom assets
	*
	* @param  string $values
	* @return string
	*/
	public static function custom($values = '')
	{
		if (not_filled($values)) {
			$var_msg = 'No value in <mark>Add::custom()</mark> tag';
			NSY_Desk::static_error_handler($var_msg);
			exit();
		} else {
			echo $values;
		}
	}

}
