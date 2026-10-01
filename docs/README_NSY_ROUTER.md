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
7. [Named Routes & URL Generation](#named-routes--url-generation)
8. [Controller Execution](#controller-execution)
9. [CSRF Protection](#csrf-protection)
10. [Cache & Performance](#cache--performance)
11. [Error Handling](#error-handling)
12. [API Reference](#api-reference)

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

## Named Routes & URL Generation

Give a route a name at declaration, then build its URL from anywhere with the
global `route()` helper — no hard-coded paths.

```php
// Declare (names only work with the options form, Route::route())
Route::route('get', '/user/(:num)', [UserController::class, 'show'], [
    'name' => 'user.show',
]);

Route::group('/admin', function () {
    Route::route('get', '/dashboard', [AdminController::class, 'dashboard'], [
        'name' => 'admin.dashboard',
    ]);
});
```

```php
route('user.show', [5]);      // http://host/app_dir/user/5
route('admin.dashboard');      // http://host/app_dir/admin/dashboard
route('home');                 // http://host/app_dir/

// App-relative path only (no scheme/host) — for fetch(), APIs, redirects
\System\Core\NSY_RouterOptimized::url('user.show', [5]); // /user/5
```

- Params are substituted **positionally** into the route's typed placeholders
  (`:num`, `:slug`, `(:any)`, …), URL-encoded, and an omitted optional segment
  is dropped.
- An unknown name returns an empty string.
- `Route::namedRoutes()` returns every `name => path`.

> Names are declared only via `Route::route(..., ['name' => '…'])`. The plain
> `Route::get()/post()` helpers take no options, so they cannot be named.

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
// Form — csrfField derives the input name from the key (csrf_token → name="token")
echo Route::csrfField('csrf_token'); // <input type="hidden" name="token" value="...">
echo Route::csrfMeta('csrf_token');  // <meta name="csrf-token" content="...">

// Validate
Route::post('/submit', function () {
    if (!Route::validateCsrf($_POST['token'] ?? null, 'csrf_token')) {
        http_response_code(403);
        return 'Invalid token';
    }
    // handle valid request
});

// Generate token directly
$token = Route::csrf('csrf_token');

// Several tokens for one complex form
$tokens = Route::csrfTokens(['login', 'payment']); // ['login' => '…', 'payment' => '…']
echo Route::csrfFields(['login', 'payment']);      // both hidden <input> fields
```

Every generator accepts an optional `$expiration` (seconds) and `$originCheck`
(which binds the token to the client IP + User-Agent) as its last two arguments:

```php
echo Route::csrfField('login', 1800, true); // expires in 30 min, origin-bound
Route::validateCsrf($_POST['login'] ?? null, 'login', 1800, true);
```

| Method | Purpose |
|---|---|
| `Route::csrf($key, $expiration, $origin)` | Generate and store a token |
| `Route::csrfField($key, $expiration, $origin)` | Hidden `<input>` field |
| `Route::csrfMeta($key, $expiration, $origin)` | `<meta name="csrf-token">` |
| `Route::validateCsrf($token, $key, $expiration, $origin)` | Validate (reads `$_POST`/`$_GET` when `$token` is `null`) |
| `Route::csrfTokens($keys, $expiration, $origin)` | Generate several tokens (assoc array) |
| `Route::csrfFields($keys, $expiration, $origin)` | Generate several hidden fields |

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

### Error pages

When no route matches, the router renders an HTML page from
`System/Apps/Templates/Errors/<code>.php` if one exists (`404.php`, `500.php`,
`503.php`), falling back to a plain message. A JSON client (`Accept:
application/json` / AJAX) keeps the plain body. See
[Maintenance](README_MAINTENANCE.md) for the 503 page in context.

## API Reference

### `System/Core/NSY_RouterOptimized.php`

| Method | Signature | Description |
|---|---|---|
| `enableCache` | `enableCache(bool $enable=true):void` | Toggle cache |
| `configureSecurity` | `configureSecurity(array $config):void` | Merge security config |
| `group` | `group(string $base, callable $callback):void` | Prefix group |
| `name` | `name(string $name, string $uri):void` | Register a route name |
| `namedRoutes` | `namedRoutes():array` | All `name => path` |
| `url` | `url(string $name, array $params=[]):string` | Build an app-relative URL |
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

`initRouter(array $config):array`, `get/post/put/delete/patch/head/options/any/map/group`, `goto/for`, `error/haltOnMatch/dispatch`, `csrf/csrfField/csrfMeta/validateCsrf/csrfTokens/csrfFields`, `enableCache/configureSecurity`, `getCacheStats/clearCache/clearControllerPool`, `clearCaches/getPerformanceStats/monitorRoute/debugInfo`.

