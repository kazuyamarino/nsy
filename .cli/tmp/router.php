<?php
/**
 * php -S router for NSY dev server (`nsy serve`).
 * Maps /<APP_DIR>/... to the project (static files) or public/index.php (routes).
 */

$root = dirname(__DIR__, 2); // project root
$env = @include $root . '/env.php';
$appDir = (is_array($env) && !empty($env['APP_DIR'])) ? trim((string) $env['APP_DIR'], '/') : '';

$public = $root . '/public';
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$uri = $uri === '' ? '/' : $uri;

// No APP_DIR: docroot is public/
if ($appDir === '') {
	if ($uri !== '/' && is_file($public . $uri)) {
		return false; // serve static file
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
	if ($rel !== '/' && is_file($root . $rel)) {
		return false; // serve static file (docroot = project parent)
	}
	require $public . '/index.php';
	return true;
}

// Outside APP_DIR: serve a real file if it exists, otherwise 404
if ($uri !== '/' && is_file(dirname($root) . $uri)) {
	return false;
}

http_response_code(404);
echo 'Not Found';
return true;
