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
		$href = '';
		$relEsc = self::esc($rel);
		$typeEsc = self::esc($type);
		$titleEsc = self::esc($title);

		if (self::isAbsoluteUrl($filename)) {
			$href = (string) $filename;
		} elseif ($rel === 'stylesheet') {
			$href = \css_url($filename);
		} elseif ($rel === 'shortcut icon') {
			$href = \img_url($filename);
		} else {
			$var_msg = 'Please check the format of <mark>Add::link</mark> tag';
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		if (is_filled($title)) {
			echo '<link rel="' . $relEsc . '" href="' . self::esc($href) . '" type="' . $typeEsc . '" title="' . $titleEsc . '">';
		} else {
			echo '<link rel="' . $relEsc . '" href="' . self::esc($href) . '" type="' . $typeEsc . '">';
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
		$src = self::isAbsoluteUrl($filename)
			? (string) $filename
			: \js_url($filename);

		$typePart = is_filled($type) ? ' type="' . self::esc($type) . '"' : '';
		$charsetPart = is_filled($charset) ? ' charset="' . self::esc($charset) . '"' : '';
		$attrStr = self::buildAttrString($attr);

		echo '<script src="' . self::esc($src) . '"' . $typePart . $charsetPart . ($attrStr ? ' ' . $attrStr : '') . '></script>';

		return true;
	}

	// Consolidated: directory resolution via global helpers (css_url/js_url/img_url)

	/**
	 * Internal helper: check if filename is absolute URL
	 */
	private static function isAbsoluteUrl(mixed $filename): bool
	{
		$val = strtolower((string) $filename);
		return (strpos($val, 'http://') === 0 || strpos($val, 'https://') === 0 || strpos((string) $filename, '//') === 0);
	}

	/**
	 * Internal helper: escape attribute value
	 */
	private static function esc(mixed $value): string
	{
		return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
	}

	/**
	 * Internal helper: build attribute string from array|string
	 */
	private static function buildAttrString(mixed $attr): string
	{
		if (is_array($attr)) {
			$parts = [];
			foreach ($attr as $k => $v) {
				if (not_filled($v)) continue;
				$kSan = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) $k);
				$parts[] = $kSan . '="' . self::esc($v) . '"';
			}
			return implode(' ', $parts);
		}
		return trim((string) $attr);
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
