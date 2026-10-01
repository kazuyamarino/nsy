<?php
/**
 * php -S router for the NSY dev server (`nsy serve`).
 *
 * Maps /<APP_DIR>/... to public/ static assets, or to public/index.php for
 * routes. Dev-only: project files (env.php, composer.json, System/, logs) are
 * never served — only files that live under public/.
 */

$root = dirname(__DIR__, 2); // project root
$env = @include $root . '/env.php';
$appDir = (is_array($env) && !empty($env['APP_DIR'])) ? trim((string) $env['APP_DIR'], '/') : '';

$public = $root . '/public';
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$uri = ($uri === '' || $uri === false) ? '/' : $uri;

/** Reject traversal, dotfiles and framework-internal paths. */
$blocked = static function (string $path): bool {
	if (str_contains($path, '..') || preg_match('#(^|/)\.#', $path)) {
		return true;
	}

	return (bool) preg_match('#(^|/)(System|Storage)/#', $path);
};

// No APP_DIR: docroot is public/, so serve files straight from it.
if ($appDir === '') {
	if ($blocked($uri)) {
		http_response_code(404);
		echo 'Not Found';
		return true;
	}
	if ($uri !== '/' && is_file($public . $uri)) {
		return false; // php -S serves the file
	}
	require $public . '/index.php';
	return true;
}

$prefix = '/' . $appDir;

if ($uri === $prefix) {
	$uri = '/';
}

if (str_starts_with($uri, $prefix . '/')) {
	$rel = substr($uri, strlen($prefix)); // starts with "/"

	if ($blocked($rel)) {
		http_response_code(404);
		echo 'Not Found';
		return true;
	}

	// Static assets live under /public/... ; everything else is a route.
	if (str_starts_with($rel, '/public/') && is_file($root . $rel)) {
		return false; // php -S serves it (docroot = project parent)
	}

	require $public . '/index.php';
	return true;
}

http_response_code(404);
echo 'Not Found';
return true;
