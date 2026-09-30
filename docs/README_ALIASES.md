# NSY Class Aliases — User Tutorial

Class aliases map a short, memorable name (or a backwards-compatible namespace)
to the real class that implements it. Aliases are registered **once** during
boot, so you can write `Route::get(...)`, `Add::link(...)`, `Mig::connect(...)` or
`Validator::make(...)` without importing the fully-qualified class.

> **Where the setting lives:** `System/Config/App.php` → the `'aliases'` array.
> The aliased classes themselves live throughout `System/` — `System/Core`,
> `System/Libraries`, `System/Helpers` — and anything under `System/Apps/...` can
> be aliased the same way.

## Table of Contents

1. [How It Works](#how-it-works)
2. [Default Aliases](#default-aliases)
3. [Using an Alias](#using-an-alias)
4. [Adding Your Own Alias](#adding-your-own-alias)
5. [Runtime Aliases](#runtime-aliases)
6. [Rules & Gotchas](#rules--gotchas)
7. [Manager API Reference](#manager-api-reference)
8. [Quick Reference](#quick-reference)

---

## How It Works

| Step | File |
|---|---|
| 1. Boot | `NSY_System` → `NSY_SystemLoader::loadSystemFiles()` |
| 2. Load alias bootstrap | `System/Libraries/Aliases.php` (listed in `NSY_SystemLoader::$systemConfig['libraries']`) |
| 3. Read config | `config_app('aliases')` → `System/Config/App.php` |
| 4. Create aliases | `System/Core/NSY_AliasManager::loadAliases()` → `class_alias($target, $alias)` |

`Aliases.php` asks `NSY_AliasManager` first. If the manager returns `false`
(invalid config or an exception), it falls back to a simple loop over the same
array. The manager caches the result and validates every pair before calling
`class_alias()`, so a typo logs an error instead of breaking boot:

```php
// System/Libraries/Aliases.php
if (!NSY_AliasManager::loadAliases()) {
	$arr = config_app('aliases');
	foreach ($arr as $alias => $target) {
		if (class_exists($target)) {
			class_alias($target, $alias);
		}
	}
}
```

---

## Default Aliases

Configured in `System/Config/App.php`:

| Alias | Target class |
|---|---|
| `Route` | `System\Helpers\RouterHelper` |
| `Add` | `System\Core\NSY_AssetManager` |
| `System\Migrations\Mig` | `System\Core\NSY_Migration` |
| `System\Vendor\Curl` | `System\Libraries\Curl` |
| `System\Vendor\Carbon` | `Carbon\Carbon` |
| `System\Vendor\Almana` | `System\Libraries\Encryption` |
| `System\Libraries\Facades\Cookie` | `System\Libraries\Cookie` |
| `System\Libraries\Facades\Session` | `System\Libraries\Session` |
| `System\Libraries\Validator` | `Rakit\Validation\Validator` |

The `System\Vendor\*` and `System\Libraries\Facades\*` entries are
backwards-compatible namespaces, kept so older code keeps working.

---

## Using an Alias

```php
Route::get('/users', [UserController::class, 'index']);  // System\Helpers\RouterHelper
Add::link('main.css');                                   // System\Core\NSY_AssetManager
Mig::connect()->createTable('users', [...]);             // System\Core\NSY_Migration
$v = Validator::make($data, $rules);                     // Rakit\Validation\Validator
```

Because aliases are global, they can be used in controllers, models, routes,
templates and CLI scripts without a `use` statement.

---

## Adding Your Own Alias

Add one line to the `'aliases'` array in `System/Config/App.php`:

```php
'aliases' => [
	// ...
	'MyService' => System\Apps\General\Services\MyService::class,
],
```

Then use it anywhere:

```php
MyService::run();
```

> Use the `::class` constant so the IDE and static analysis can follow the link.
> The target must be autoloadable (PSR-4 under `System\`) when boot runs.

---

## Runtime Aliases

Register an alias on demand, without editing config:

```php
use System\Core\NSY_AliasManager;

NSY_AliasManager::createSingleAlias('Report', ReportService::class); // true on success
NSY_AliasManager::createSingleAlias('Legacy');                        // target read from config
```

---

## Rules & Gotchas

- The **target class must exist** (or be autoloadable) — otherwise the alias is
  skipped and an error is logged.
- An alias name is **global** like any PHP class name; do not reuse one that
  already exists.
- `config_app('aliases')` must be an **array**; anything else is reported through
  `NSY_Desk::staticErrorHandler()`.
- Aliases are registered at **boot**, not lazily, unless you call
  `createSingleAlias()` yourself.
- `debugInfo()` returns `null` in production; use `generateReport()` for stats.

---

## Manager API Reference

`System\Core\NSY_AliasManager` — all methods are static:

| Method | Purpose |
|---|---|
| `loadAliases(): bool` | Register every alias from config (cached) |
| `createSingleAlias(string $alias, ?string $target = null): bool` | Register one alias (target falls back to config) |
| `hasAlias(string $alias): bool` | Whether the alias is registered |
| `getTargetClass(string $alias): ?string` | Mapped target class, or `null` |
| `getCachedAliases(): array` | Alias → target map |
| `getPerformanceStats(): array` | Counters and load time |
| `generateReport(): array` | Summary and performance report |
| `debugInfo(): ?array` | Debug info (development only) |
| `clearCache(): void` | Reset the cache (for tests) |

---

## Quick Reference

| I want to… | Do |
|---|---|
| Use a short name | Reference it directly (`Route::`, `Add::`, `Mig::`, …) |
| Add a new alias | Add `'Alias' => Target::class` to `System/Config/App.php:aliases` |
| Add one at runtime | `NSY_AliasManager::createSingleAlias('Alias', Target::class)` |
| Inspect an alias | `NSY_AliasManager::getTargetClass('Route')` |
| Check config health | `NSY_AliasManager::generateReport()` |

Related: `System/Config/App.php` (`aliases`), `System/Libraries/Aliases.php`,
`System/Core/NSY_AliasManager.php`, `System/Core/NSY_SystemLoader.php`.
