# NSY Router Documentation

> Engine: `System/Core/NSY_RouterOptimized.php` · Facade: `System/Helpers/RouterHelper.php` (alias `Route`) · Loader: `System/Core/NSY_RouteLoader.php` · File cache: `System/Core/NSY_RouteCacheManager.php`
> **Complete example:** 14 sections in `System/Routes/Route_Example.php` (excluded from loader, copy-paste ready)

## Table of Contents

1. [Quick Start](#quick-start)
2. [Where to Define Routes](#where-to-define-routes)
3. [Basic Routing](#basic-routing)
4. [HTTP Methods](#http-methods)
5. [Parameters & Patterns](#parameters--patterns)
6. [Route Groups](#route-groups)
7. [Controller Execution](#controller-execution)
8. [CSRF Protection](#csrf-protection)
9. [Cache & Performance](#cache--performance)
10. [Error Handling](#error-handling)
11. [API Reference](#api-reference)

---

## Quick Start

```php
<?php
// System/Routes/General.php
Route::initRouter([
    'cache_enabled' => true,
    'security' => ['validate_params' => true, 'sanitize_input' => true],
]);

Route::get('/hello', function () {
    echo 'Hello World!';
});

Route::get('/users/(:num)', [UserController::class, 'show']);
```

```php
// Test with a fake request
$_SERVER['REQUEST_URI'] = '/nsy/hello';
$_SERVER['REQUEST_METHOD'] = 'GET';
Route::dispatch(); // → "Hello World!"
```

## Where to Define Routes

| File | Purpose |
|---|---|
| `System/Routes/General.php` | Main routes |
| `System/Routes/Modules.php` | HMVC module routes |
| `System/Routes/*.php` | Auto-discovered (except `Route_Example.php`, `TestRoute.php`) |
| `System/Core/NSY_Migration_Route.php` | Migration routes (`/migup=(:any)`, `/migdown=(:any)`) |

## Basic Routing

```php
Route::get('/home', function () {
    return 'Welcome!';
});

Route::get('/users', [UserController::class, 'index']);
Route::post('/contact', [ContactController::class, 'submit']);
Route::any('/webhook', [WebhookController::class, 'receive']);
Route::map(['GET', 'POST'], '/form', [FormController::class, 'handle']);
```

All paths are prefixed with `config_app('app_dir')` (e.g. `/nsy`).

## HTTP Methods

```php
Route::get('/users', ...);
Route::post('/users', ...);
Route::put('/users/(:num)', ...);
Route::patch('/users/(:num)', ...);
Route::delete('/users/(:num)', ...);
Route::head('/users/(:num)', ...);
Route::options('/users', ...);
Route::any('/catch', ...);
Route::map(['GET', 'POST'], '/x', ...);
```

## Parameters & Patterns

```php
Route::get('/user/(:num)', [UserController::class, 'show']);          // /user/123
Route::get('/post/(:slug)', [PostController::class, 'show']);         // /post/my-post
Route::get('/user/(:num)/post/(:slug)', [PostController::class, 'userPost']);
```

| Pattern | Regex | Example |
|---|---|---|
| `:all` | `.*` | `api/v1/users/123` |
| `:any` | `[^/]+` | `john-doe` |
| `:slug` | `[a-z0-9-]+` | `my-blog-post` |
| `:uslug` | `[\w-]+` | `My_Blog-Post` |
| `:num` | `[0-9]+` | `123` |
| `:alpha` | `[A-Za-z]+` | `john` |
| `:alnum` | `[0-9A-Za-z]+` | `user123` |
| `:date` | `[0-9]{4}-[0-9]{2}-[0-9]{2}` | `2024-01-15` |

```php
Route::get('/user/(:num)/post/(:slug)', function ($id, $slug) {
    echo $id . ' ' . $slug;
});
```

## Route Groups

```php
Route::group('/api/v1', function () {
    Route::get('/users', [ApiController::class, 'getUsers']);      // → /nsy/api/v1/users
    Route::post('/users', [ApiController::class, 'createUser']);
    Route::get('/users/(:num)', [ApiController::class, 'getUser']);
});

// Nested groups
Route::group('/admin', function () {
    Route::group('/users', function () {
        Route::get('/', [AdminUserController::class, 'index']); // /nsy/admin/users/
    });
});
```

## Controller Execution

Forward from a closure to a controller:

```php
Route::get('/test/index', function () {
    Route::goto([C_Test_Route::class, 'index']);
});

Route::get('/user/(:num)', function ($id) {
    Route::goto([UserController::class, 'show'], $id);
    Route::goto([UserController::class, 'show'], [$id, $extra]);
});

// Instance alias (same behavior)
Route::for([UserController::class, 'processData'], ['data' => $clean]);
```

## CSRF Protection

```php
// Form
echo Route::csrfField('csrf_token'); // <input type="hidden" name="csrf_token" value="...">
echo Route::csrfMeta('csrf_token');  // <meta name="csrf-token" content="...">

// Validate
Route::post('/submit', function () {
    if (!Route::validateCsrf($_POST['csrf_token'] ?? null, 'csrf_token')) {
        http_response_code(403);
        return 'Invalid token';
    }
    // handle valid request
});

// Generate token directly
$token = Route::csrf('csrf_token');
```

## Cache & Performance

```php
Route::enableCache(true);
$stats = Route::getCacheStats();
// ['cached_routes'=>3,'total_routes'=>10,'cache_enabled'=>true]

Route::clearCache();
Route::clearControllerPool();
Route::clearCaches(); // clears all layers

Route::monitorRoute('/heavy', fn() => HeavyController::process());
Route::debugInfo();
```

| Cache | Clear with |
|---|---|
| Dispatch cache (uri+method) | `Route::clearCache()` |
| Compiled regex | auto on new route, or `clearCache()` |
| Controller pool | `Route::clearControllerPool()` |
| Discovered file list | `Route::clearCaches()` |
| File cache | `Route::clearCaches()` |

## Error Handling

```php
Route::error(function () {
    http_response_code(404);
    echo 'Page not found';
});

Route::error('/fallback');
Route::haltOnMatch(true);  // stop after first match (default)
Route::haltOnMatch(false); // continue
```

## API Reference

### `System/Core/NSY_RouterOptimized.php`

| Method | Signature | Description |
|---|---|---|
| `enableCache` | `enableCache(bool $enable=true):void` | Toggle cache |
| `configureSecurity` | `configureSecurity(array $config):void` | Merge security config |
| `group` | `group(string $base, callable $callback):void` | Prefix group |
| `goto` | `goto(array $target, mixed $vars=[]):mixed` | Execute controller |
| `for` | `for(array $target, mixed $vars=[]):mixed` | Alias of `goto` |
| `error` | `error(callable|string $callback):void` | 404 handler |
| `haltOnMatch` | `haltOnMatch(bool $flag=true):void` | Stop after first match |
| `dispatch` | `dispatch():void` | Match and execute |
| `clearCache` | `clearCache():void` | Clear route cache |
| `clearControllerPool` | `clearControllerPool():void` | Clear pool |
| `getCacheStats` | `getCacheStats():array` | Cache stats |

### `System/Core/NSY_RouteLoader.php`

| Method | Description |
|---|---|
| `loadRoutes():bool` | Discover and require route files |
| `getLoadedFiles():string[]` | List loaded files |
| `generateReport():array` | Summary and configuration |
| `clearCache():void` | Clear file list cache |

### `System/Core/NSY_RouteCacheManager.php`

| Method | Description |
|---|---|
| `init(?string $cacheDir)` | Set temp dir |
| `getCacheStats():array` | File cache info |
| `clearCache():bool` | Delete cache file |
| `getPerformanceStats():array` | Avg time/memory (last 1000) |

### `System/Helpers/RouterHelper.php`

`initRouter(array $config):array`, `get/post/put/delete/patch/head/options/any/map/group`, `goto/for`, `error/haltOnMatch/dispatch`, `enableCache/configureSecurity`, `getCacheStats/clearCache/clearControllerPool`, `clearCaches/getPerformanceStats/monitorRoute/debugInfo`.

