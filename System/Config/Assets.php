<?php

declare(strict_types=1);

/**
 * Attention, don't try to change the structure of the code, delete, or change.
 * Because there is some code connected to the NSY system. So, be careful.
 *
 * Hi Welcome to NSY Assets Manager.
 * The easiest & best assets manager in history.
 * Made with love by Vikry Yuansah.
 *
 * How to use it? Simply follow this :
 * https://github.com/kazuyamarino/nsy-docs/blob/master/USERGUIDE.md#introducting-to-nsy-assets-manager
 *
 * @see System\Core\NSY_AssetManager
 */

function header_assets(): void
{
	// Site Title
	Add::custom('<title>' . get_title() . ' ' . get_version() . ' | ' . get_codename() . '</title>');

	// Meta Tag
	Add::meta('charset="utf-8"');
	Add::meta('http-equiv="x-ua-compatible"', 'ie=edge');
	Add::meta('name="description"', get_desc());
	Add::meta('name="keywords"', get_keywords());
	Add::meta('name="author"', get_author());
	Add::meta('name="viewport"', 'width=device-width, initial-scale=1, shrink-to-fit=no');

	// Favicon
	Add::link('favicon.png', 'shortcut icon');

	// Main Style
	Add::link('main.css', 'stylesheet', 'text/css');
}

function footer_assets(): void
{
	// System JS
	Add::script('config/system.js', 'text/javascript', 'UTF-8');

	// Main JS
	Add::script('main.js', 'text/javascript', 'UTF-8');

	// Docs index search (welcome page) — no-op when the search box is absent
	Add::script('docs.js', 'text/javascript', 'UTF-8');
}
