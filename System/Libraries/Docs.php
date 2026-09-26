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
	 * @var array<string,array{file:string,category:string,emoji:string,title:string,api:string,summary:string}>
	 */
	private const MANIFEST = [
		'overview' => [
			'file' => 'OVERVIEW.md',
			'category' => 'Getting Started',
			'emoji' => '📖',
			'title' => 'Overview',
			'api' => 'Composer · CLI · Config',
			'summary' => 'Start here: installation, environment variables, MVC/HMVC layout, CLI commands and deployment.',
		],
		'load-assets' => [
			'file' => 'README_LOAD_AND_ASSETMANAGER.md',
			'category' => 'Core',
			'emoji' => '🧩',
			'title' => 'Load & Asset Manager',
			'api' => 'Load::view() · Add::link()',
			'summary' => 'Razr views, templates, models, HMVC loading and cache-busted css/js/img URLs.',
		],
		'router' => [
			'file' => 'README_NSY_ROUTER.md',
			'category' => 'Core',
			'emoji' => '🛣️',
			'title' => 'Router',
			'api' => 'Route::get/post/group',
			'summary' => 'Route registration, typed params, groups, middleware and security levels.',
		],
		'model' => [
			'file' => 'README_MODEL.md',
			'category' => 'Core',
			'emoji' => '🗃️',
			'title' => 'Model & DB',
			'api' => 'DB::query() · NSY_DB::connect()',
			'summary' => 'Unified single-source database access, models and fetch styles.',
		],
		'migration' => [
			'file' => 'README_MIGRATION.md',
			'category' => 'Core',
			'emoji' => '🗄️',
			'title' => 'Migration',
			'api' => 'Mig::create_table()',
			'summary' => 'Chainable schema builder, DDL helpers and the run:migrate CLI runner.',
		],
		'query-builder' => [
			'file' => 'README_QUERY_BUILDER.md',
			'category' => 'Core',
			'emoji' => '⚡',
			'title' => 'Query Builder',
			'api' => 'qb()->whereIn()->paginate()',
			'summary' => 'Fluent SQL builder: where, joins, grouping, pagination and raw queries.',
		],
		'libraries' => [
			'file' => 'README_LIBRARIES.md',
			'category' => 'Reference',
			'emoji' => '📚',
			'title' => 'Libraries',
			'api' => 'File · LanguageCode · Validate',
			'summary' => 'File management, language codes, validation and Query Builder internals.',
		],
		'helpers' => [
			'file' => 'README_HELPERS_GLOBAL.md',
			'category' => 'Reference',
			'emoji' => '🔧',
			'title' => 'Global Helpers',
			'api' => 'base_url() · is_filled()',
			'summary' => 'URI, asset, config, string and array helpers — all env-aware.',
		],
		'ci-helpers' => [
			'file' => 'README_CODEIGNITER_HELPERS.md',
			'category' => 'Reference',
			'emoji' => '🎛️',
			'title' => 'CodeIgniter Helpers',
			'api' => 'is_php() · date_range()',
			'summary' => '27 compatibility helpers ported from CodeIgniter, available globally.',
		],
		'security' => [
			'file' => 'README_SECURITY_MIDDLEWARE.md',
			'category' => 'Reference',
			'emoji' => '🔒',
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
