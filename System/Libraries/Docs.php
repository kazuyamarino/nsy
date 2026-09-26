<?php
declare(strict_types=1);

namespace System\Libraries;

/**
 * Documentation registry for the NSY in-app doc viewer.
 *
 * Maps a human-friendly slug to a Markdown file in /docs, groups the files by
 * category for navigation, and renders them to HTML through {@see Markdown}.
 */
class Docs
{
	/** Category display order. */
	private const CATEGORY_ORDER = [
		'Getting Started',
		'Core',
		'Reference',
	];

	/**
	 * Ordered manifest: slug => metadata. The order here drives navigation.
	 *
	 * @var array<string,array{file:string,category:string,icon:string,title:string,api:string,summary:string}>
	 */
	private const MANIFEST = [
		'overview' => [
			'file' => 'OVERVIEW.md',
			'category' => 'Getting Started',
			'icon' => 'book',
			'title' => 'Overview',
			'api' => 'Composer · CLI · Config',
			'summary' => 'Start here: installation, environment variables, MVC/HMVC layout, CLI commands and deployment.',
		],
		'load-assets' => [
			'file' => 'README_LOAD_AND_ASSETMANAGER.md',
			'category' => 'Core',
			'icon' => 'layers',
			'title' => 'Load & Asset Manager',
			'api' => 'Load::view() · Add::link()',
			'summary' => 'Razr views, templates, models, HMVC loading and cache-busted css/js/img URLs.',
		],
		'router' => [
			'file' => 'README_NSY_ROUTER.md',
			'category' => 'Core',
			'icon' => 'route',
			'title' => 'Router',
			'api' => 'Route::get/post/group',
			'summary' => 'Route registration, typed params, groups, middleware and security levels.',
		],
		'model' => [
			'file' => 'README_MODEL.md',
			'category' => 'Core',
			'icon' => 'database',
			'title' => 'Model & DB',
			'api' => 'DB::query() · NSY_DB::connect()',
			'summary' => 'Unified single-source database access, models and fetch styles.',
		],
		'migration' => [
			'file' => 'README_MIGRATION.md',
			'category' => 'Core',
			'icon' => 'migrate',
			'title' => 'Migration',
			'api' => 'Mig::create_table()',
			'summary' => 'Chainable schema builder, DDL helpers and the run:migrate CLI runner.',
		],
		'query-builder' => [
			'file' => 'README_QUERY_BUILDER.md',
			'category' => 'Core',
			'icon' => 'bolt',
			'title' => 'Query Builder',
			'api' => 'qb()->whereIn()->paginate()',
			'summary' => 'Fluent SQL builder: where, joins, grouping, pagination and raw queries.',
		],
		'libraries' => [
			'file' => 'README_LIBRARIES.md',
			'category' => 'Reference',
			'icon' => 'library',
			'title' => 'Libraries',
			'api' => 'File · LanguageCode · Validate',
			'summary' => 'File management, language codes, validation and Query Builder internals.',
		],
		'helpers' => [
			'file' => 'README_HELPERS_GLOBAL.md',
			'category' => 'Reference',
			'icon' => 'gear',
			'title' => 'Global Helpers',
			'api' => 'base_url() · is_filled()',
			'summary' => 'URI, asset, config, string and array helpers — all env-aware.',
		],
		'ci-helpers' => [
			'file' => 'README_CODEIGNITER_HELPERS.md',
			'category' => 'Reference',
			'icon' => 'sliders',
			'title' => 'CodeIgniter Helpers',
			'api' => 'is_php() · date_range()',
			'summary' => '27 compatibility helpers ported from CodeIgniter, available globally.',
		],
		'security' => [
			'file' => 'README_SECURITY_MIDDLEWARE.md',
			'category' => 'Reference',
			'icon' => 'shield',
			'title' => 'Security Middleware',
			'api' => 'sanitizeInput() · ensureSession()',
			'summary' => 'Input sanitization, session guard, security headers and CSRF basics.',
		],
	];

	public static function docsDir(): string
	{
		return dirname(__DIR__, 2) . '/docs';
	}

	/**
	 * Inline monoline SVG icon for a doc (rendered in the current text colour).
	 */
	public static function icon(string $name): string
	{
		$icons = [
			'book' => '<rect x="4" y="4" width="16" height="16" rx="2"/><path d="M8 4v16"/><path d="M12 9h5"/><path d="M12 13h5"/>',
			'layers' => '<path d="M12 3 3 8l9 5 9-5-9-5z"/><path d="m3 13 9 5 9-5"/>',
			'route' => '<circle cx="6" cy="18" r="2"/><circle cx="18" cy="6" r="2"/><path d="M8 18h6a4 4 0 0 0 4-4V8"/>',
			'database' => '<ellipse cx="12" cy="6" rx="7" ry="3"/><path d="M5 6v6c0 1.7 3.1 3 7 3s7-1.3 7-3V6"/><path d="M5 12v6c0 1.7 3.1 3 7 3s7-1.3 7-3v-6"/>',
			'migrate' => '<path d="M8 4v16"/><path d="m4 8 4-4 4 4"/><path d="M16 4v16"/><path d="m12 16 4 4 4-4"/>',
			'bolt' => '<path d="M13 2 4 14h6l-1 8 9-12h-6l1-8z"/>',
			'library' => '<path d="M4 5h6v15H4z"/><path d="M10 5h4v15h-4z"/><path d="m14.5 5.5 4 14"/>',
			'gear' => '<circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3M4.9 4.9 7 7M17 17l2.1 2.1M19.1 4.9 17 7M7 17l-2.1 2.1"/>',
			'sliders' => '<path d="M4 6h9"/><path d="M19 6h1"/><circle cx="15" cy="6" r="2"/><path d="M4 12h3"/><path d="M13 12h7"/><circle cx="9" cy="12" r="2"/><path d="M4 18h9"/><path d="M19 18h1"/><circle cx="15" cy="18" r="2"/>',
			'shield' => '<path d="M12 3 5 6v5c0 4.2 3 7.9 7 9 4-1.1 7-4.8 7-9V6l-7-3z"/><path d="m9 12 2 2 4-4"/>',
		];

		$inner = $icons[$name] ?? $icons['book'];

		return '<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" '
			. 'stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">'
			. $inner . '</svg>';
	}

	/**
	 * All docs, enriched with slug/path/exists/lines/size, in manifest order.
	 *
	 * @return array<string,array<string,mixed>>
	 */
	public static function all(): array
	{
		$dir = self::docsDir();
		$out = [];

		foreach (self::MANIFEST as $slug => $meta) {
			$path = $dir . '/' . $meta['file'];
			$exists = is_file($path);
			$lines = $exists ? count((array) @file($path)) : 0;

			$out[$slug] = $meta + [
				'slug' => $slug,
				'path' => $path,
				'exists' => $exists,
				'lines' => $lines,
				'size' => $exists ? (int) @filesize($path) : 0,
				'icon_svg' => self::icon((string) ($meta['icon'] ?? 'book')),
			];
		}

		return $out;
	}

	/**
	 * Docs grouped by category, in CATEGORY_ORDER (empty categories removed).
	 *
	 * @return array<string,array<string,array<string,mixed>>>
	 */
	public static function categories(): array
	{
		$grouped = [];
		foreach (self::CATEGORY_ORDER as $category) {
			$grouped[$category] = [];
		}

		foreach (self::all() as $slug => $doc) {
			$category = (string) $doc['category'];
			if (!isset($grouped[$category])) {
				$grouped[$category] = [];
			}
			$grouped[$category][$slug] = $doc;
		}

		return array_filter($grouped, static fn(array $items): bool => $items !== []);
	}

	/**
	 * @return array<string,mixed>|null
	 */
	public static function find(string $slug): ?array
	{
		$slug = self::normalizeSlug($slug);
		if ($slug === '' || !isset(self::MANIFEST[$slug])) {
			return null;
		}

		$all = self::all();
		$doc = $all[$slug] ?? null;

		return ($doc !== null && $doc['exists']) ? $doc : null;
	}

	/**
	 * Render a doc to HTML with its TOC.
	 *
	 * @return array{doc:array<string,mixed>,html:string,toc:array<int,array{level:int,text:string,id:string}>}|null
	 */
	public static function render(string $slug): ?array
	{
		$doc = self::find($slug);
		if ($doc === null) {
			return null;
		}

		$markdown = @file_get_contents($doc['path']);
		if ($markdown === false) {
			return null;
		}

		$parsed = Markdown::toHtmlWithToc($markdown);

		return [
			'doc' => $doc,
			'html' => $parsed['html'],
			'toc' => $parsed['toc'],
		];
	}

	/**
	 * Previous and next doc relative to the given slug.
	 *
	 * @return array{prev:?array<string,mixed>,next:?array<string,mixed>}
	 */
	public static function neighbors(string $slug): array
	{
		$slug = self::normalizeSlug($slug);
		$all = self::all();
		$slugs = array_keys($all);
		$index = array_search($slug, $slugs, true);

		if ($index === false) {
			return ['prev' => null, 'next' => null];
		}

		return [
			'prev' => $index > 0 ? $all[$slugs[$index - 1]] : null,
			'next' => $index < count($slugs) - 1 ? $all[$slugs[$index + 1]] : null,
		];
	}

	private static function normalizeSlug(string $slug): string
	{
		$slug = strtolower(trim($slug));

		return (string) preg_replace('/[^a-z0-9-]+/', '', $slug);
	}
}
