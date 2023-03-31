<?php

namespace System\Core;

/**
 * This is the core of NSY Asset Manager
 * Attention, don't try to change the structure of the code, delete, or change.
 * Because there is some code connected to the NSY system. So, be careful.
 */
class NSY_AssetManager
{

	/**
	 * Generate meta tags
	 *
	 * @param  mixed $attr
	 * @param  mixed $content
	 * @return bool
	 */
	public static function meta(mixed $attr = '', mixed $content = '')
	{
		// if content is empty
		if (not_filled($content)) {
			// then show meta tags without content
			echo '<meta ' . $attr . '>';
		} elseif (is_filled($attr) || is_filled($content)) {
			// then show meta tags with content
			echo '<meta ' . $attr . ' content="' . $content . '">';
		} else {
			$var_msg = 'Please check the format of <mark>Add::meta</mark> tag';
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		return true;
	}

	/**
	 * Generate link tags
	 *
	 * @param  mixed $filename
	 * @param  mixed $rel
	 * @param  mixed $type
	 * @param  mixed $title
	 * @return bool
	 */
	public static function link(mixed $filename = '', mixed $rel = '', mixed $type = '', mixed $title = '')
	{
		// if content is empty
		if (strpos($filename, 'http') !== false || strpos($filename, 'https') !== false) {
			echo '<link rel="' . $rel . '" href="' . $filename . '" type="' . $type . '">';
		} elseif (is_filled($title)) {
			echo '<link rel="' . $rel . '" href="' . $filename . '" type="' . $type . '" title="' . $title . '">';
		} elseif ($rel == 'stylesheet') {
			echo '<link rel="' . $rel . '" href="' . CSS_DIR . $filename . '" type="' . $type . '">';
		} elseif ($rel == 'shortcut icon') {
			echo '<link rel="' . $rel . '" href="' . IMG_DIR . $filename . '">';
		} else {
			$var_msg = 'Please check the format of <mark>Add::link</mark> tag';
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		return true;
	}

	/**
	 * Generate script tags filename
	 *
	 * @param  mixed $filename
	 * @param  mixed $type
	 * @param  mixed $charset
	 * @param  mixed $attr
	 * @return bool
	 */
	public static function script(mixed $filename = '', mixed $type = '', mixed $charset = '', mixed $attr = '')
	{
		// if charset is empty
		if (strpos($filename, 'http') !== false || strpos($filename, 'https') !== false) {
			// if charset is empty
			if (not_filled($charset)) {
				// then show script tags without charset
				echo '<script src="' . $filename . '" type="' . $type . '" ' . $attr . '></script>';
			} else {
				// then show script tags with charset
				echo '<script src="' . $filename . '" type="' . $type . '" charset="' . $charset . '" ' . $attr . '></script>';
			}
		} elseif (not_filled($charset)) {
			// then show script tags without charset
			echo '<script src="' . JS_DIR . $filename . '" type="' . $type . '" ' . $attr . '></script>';
		} else {
			// then show script tags with charset
			echo '<script src="' . JS_DIR . $filename . '" type="' . $type . '" charset="' . $charset . '" ' . $attr . '></script>';
		}

		return true;
	}

	/**
	 * Generate custom assets
	 *
	 * @param  mixed $values
	 * @return bool
	 */
	public static function custom(mixed $values = '')
	{
		if (not_filled($values)) {
			$var_msg = 'No value in <mark>Add::custom()</mark> tag';
			NSY_Desk::static_error_handler($var_msg);
			exit();
		} else {
			echo $values;
		}

		return true;
	}
}
