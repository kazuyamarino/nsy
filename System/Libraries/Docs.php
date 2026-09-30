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
		'deploy-hosting' => [
			'file' => 'README_DEPLOY_HOSTING.md',
			'category' => 'Getting Started',
			'icon' => 'server',
			'title' => 'Deploy to Hosting',
			'api' => 'public_html · APP_DIR · chmod',
			'summary' => 'Shared hosting setup: System/ outside public_html, env keys, writable dirs, mod_rewrite/AllowOverride.',
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
			'api' => 'Mig::createTable()',
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
		'logging' => [
			'file' => 'README_LOGGING.md',
			'category' => 'Reference',
			'icon' => 'log',
			'title' => 'Logging',
			'api' => 'LogManager::channel() · PSR-3',
			'summary' => 'Dependency-light PSR-3 file logger: channels, JSONL, rotation and retention.',
		],
		'json' => [
			'file' => 'README_JSON.md',
			'category' => 'Reference',
			'icon' => 'braces',
			'title' => 'JSON',
			'api' => 'Json::read() · Json::write()',
			'summary' => 'Read/write JSON files atomically, plus encode/decode helpers.',
		],
		'cookie' => [
			'file' => 'README_COOKIE.md',
			'category' => 'Reference',
			'icon' => 'cookie',
			'title' => 'Cookie',
			'api' => 'Cookie::set() · Cookie::get()',
			'summary' => 'Secure-by-default cookie helper (HttpOnly, SameSite, Secure).',
		],
		'encryption' => [
			'file' => 'README_ENCRYPTION.md',
			'category' => 'Reference',
			'icon' => 'lock',
			'title' => 'Encryption',
			'api' => 'Encryption::encrypt() · decrypt()',
			'summary' => 'AES-256-GCM authenticated encryption with a versioned envelope.',
		],
		'session' => [
			'file' => 'README_SESSION.md',
			'category' => 'Reference',
			'icon' => 'key',
			'title' => 'Session',
			'api' => 'Session::start() · Session::get()',
			'summary' => 'Native session wrapper: start, get/set, regenerate and flash values.',
		],
		'curl' => [
			'file' => 'README_CURL.md',
			'category' => 'Reference',
			'icon' => 'globe',
			'title' => 'Curl',
			'api' => 'Curl::get() · Curl::post()',
			'summary' => 'Chainable ext-curl HTTP client with JSON auto-decode and secure defaults.',
		],
		'validation' => [
			'file' => 'README_VALIDATION.md',
			'category' => 'Reference',
			'icon' => 'check',
			'title' => 'Validation',
			'api' => 'Validator::make() · validate()',
			'summary' => 'Rule-based validation via rakit/validation, exposed as System\\Libraries\\Validator.',
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
		'dependencies' => [
			'file' => 'README_DEPENDENCIES.md',
			'category' => 'Reference',
			'icon' => 'package',
			'title' => 'Dependencies',
			'api' => 'composer install · show',
			'summary' => 'Every Composer package NSY installs — runtime, dev and transitive.',
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
			'server' => '<rect x="3" y="4" width="18" height="7" rx="2"/><rect x="3" y="13" width="18" height="7" rx="2"/><path d="M7 7.5h.01M7 16.5h.01"/>',
			'braces' => '<path d="M8 3H7a2 2 0 0 0-2 2v4a2 2 0 0 1-2 2 2 2 0 0 1 2 2v4a2 2 0 0 0 2 2h1"/><path d="M16 3h1a2 2 0 0 1 2 2v4a2 2 0 0 0 2 2 2 2 0 0 0-2 2v4a2 2 0 0 1-2 2h-1"/>',
			'cookie' => '<path d="M12 3a9 9 0 1 0 9 9 4 4 0 0 1-5-5 3 3 0 0 1-4-4z"/><circle cx="8.5" cy="10.5" r=".8"/><circle cx="11.5" cy="14.5" r=".8"/><circle cx="7.5" cy="15.5" r=".8"/>',
			'lock' => '<rect x="4" y="10" width="16" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/><path d="M12 14v3"/>',
			'key' => '<circle cx="8" cy="15" r="4"/><path d="m10.8 12.2 8-8"/><path d="m17 4 3 3"/><path d="m15 6 2 2"/>',
			'globe' => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18"/><path d="M12 3a15 15 0 0 1 0 18"/><path d="M12 3a15 15 0 0 0 0 18"/>',
			'check' => '<circle cx="12" cy="12" r="9"/><path d="m8 12 3 3 5-6"/>',
			'log' => '<path d="M8 6h11"/><path d="M8 12h11"/><path d="M8 18h11"/><path d="M4 6h.01"/><path d="M4 12h.01"/><path d="M4 18h.01"/>',
			'package' => '<path d="m12 3 8 4.5v9L12 21l-8-4.5v-9L12 3z"/><path d="M4 7.5 12 12l8-4.5"/><path d="M12 12v9"/>',
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
	 * The TOC holds only the numbered sections (the parser marks level-2 headings
	 * with a 'num'), which is what the "On this page" list displays. Filtering
	 * happens here rather than in Markdown so the library keeps returning a
	 * complete table of contents and the app decides how to present it.
	 *
	 * @return array{doc:array<string,mixed>,html:string,toc:array<int,array{level:int,text:string,id:string,num:string}>}|null
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
		$toc = array_values(array_filter(
			$parsed['toc'],
			static fn(array $heading): bool => !empty($heading['num'])
		));

		return [
			'doc' => $doc,
			'html' => $parsed['html'],
			'toc' => $toc,
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
