# NSY Load & Asset Manager — User Tutorial

This guide documents the core loader (`System/Core/Load.php`) and the asset manager (`System/Core/NSY_AssetManager.php`, alias `Add`) in NSY Framework.

- **Load:** `System\Core\Load` — renders MVC/HMVC views & templates via Razr, instantiates models
- **AssetManager:** `System\Core\NSY_AssetManager` (`Add`) — echoes `<meta>`, `<link>`, `<script>`, and custom HTML with safe URL resolution

Both use `NSY_System` constants with config fallbacks, so they never emit undefined-constant warnings.

## Table of Contents

1. [Load — Render Views/Templates & Models](#load-render-viewstemplates-models)
   - [Methods](#methods)
   - [Usage Examples](#usage-examples)
2. [NSY_AssetManager — Meta/Link/Script/Custom](#nsy_assetmanager-metalinkscriptcustom)
   - [Methods](#methods-1)
   - [Aliasing: Add](#aliasing-add)
   - [How URLs Are Resolved](#how-urls-are-resolved)
   - [Examples](#examples)
   - [Security Notes](#security-notes)
3. [Troubleshooting](#troubleshooting)
4. [Quick Reference](#quick-reference)

---

## Load — Render Views/Templates & Models

- **Purpose:** Render views/templates through Razr (`FilesystemLoader(get_vendor_dir())`, lazily initialized)
- **Directories:** `get_mvc_view_dir()`, `get_hmvc_view_dir()`, `get_system_tmp_dir()` — each checks `defined()` then `config_app()` fallback

### Methods

- `Load::view(?string $module, string $filename, array|object $vars = []): void`
  - `$module` empty → MVC: `get_mvc_view_dir() . $filename . '.php'`
  - `$module` set → HMVC: `get_hmvc_view_dir() . $module . '/Views/' . $filename . '.php'`
  - `$vars` validated; invalid → `NSY_Desk::static_error_handler()`

- `Load::template(string $filename, array|object $vars = []): void`
  - From `get_system_tmp_dir()` (e.g. `System/Apps/Templates/`)

- `Load::model(string $fullclass): object`
  - `new $fullclass()` after `class_exists()` check

### Usage Examples

```php
use System\Core\Load;

// MVC
Load::template('Header', $data);
Load::view(null, 'Index_Welcome', $data); // System/Apps/General/Views/Index_Welcome.php
Load::template('Footer', $data);

// HMVC
Load::view('HMVC', 'Index_Hello', $vars); // System/Apps/Modules/HMVC/Views/Index_Hello.php

// Model
$user = Load::model(\System\Apps\General\Models\M_User::class);
```

---

## NSY_AssetManager — Meta/Link/Script/Custom

### Methods

- `Add::meta(string $attr, string $content = ''): bool`
  - `Add::meta('charset="utf-8"')` → `<meta charset="utf-8">`
  - `Add::meta('name="description"', 'My site')` → `<meta name="description" content="My site">` (content escaped via `htmlspecialchars`)
  - Requires `$attr` non-empty, otherwise `static_error_handler()`

- `Add::link(string $filename, string $rel, string $type = '', string $title = ''): bool`
  - Absolute URL (`http://`, `https://`, `//`) → used directly
  - `$rel === 'stylesheet'` → `css_url($filename)`
  - `$rel === 'shortcut icon'` → `img_url($filename)`
  - Else → error. All attributes escaped.

```php
Add::link('main.css', 'stylesheet', 'text/css');
Add::link('favicon.png', 'shortcut icon', 'image/x-icon', 'Site Icon');
Add::link('https://cdn.example.com/lib.css', 'stylesheet', 'text/css');
```

- `Add::script(string $filename, string $type = '', string $charset = '', array|string $attr = ''): bool`
  - Absolute → direct, else `js_url($filename)`
  - `$attr` as array: `['defer' => 'defer', 'data-id' => '123']` → sanitized keys, escaped values

```php
Add::script('app.js', 'text/javascript');
Add::script('app.js', 'text/javascript', 'UTF-8', ['defer' => 'defer']);
Add::script('https://cdn.example.com/app.js', 'text/javascript');
```

- `Add::custom(mixed $values): bool`
  - Echoes raw HTML — use with caution (no escaping)

```php
Add::custom('<title>' . get_title() . '</title>');
```

### Aliasing: Add

```php
use System\Core\NSY_AssetManager as Add; // canonical short alias

// or fully qualified
\System\Core\NSY_AssetManager::script('main.js', 'text/javascript');
```

Configured in `System/Config/App.php:aliases` as `'Add' => System\Core\NSY_AssetManager::class` — loaded via `NSY_AliasManager` / `System/Libraries/Aliases.php`.

### How URLs Are Resolved

1. `NSY_System::initializeAssetDirectories()` defines `CSS_DIR`/`JS_DIR`/`IMG_DIR` from `config_app()` + `base_url()`
2. `css_url()`/`js_url()`/`img_url()` (`System/Core/NSY_Helpers_Global.php`) prefer `defined(CSS_DIR)` etc.
3. Fallback: `base_url() . public_dir . '/assets/' . dir` — never undefined
4. **Cache-busting otomatis:** `css_url()/js_url()/img_url()` append `?v=filemtime` bila `!str_contains('?') && !str_starts_with('http') && is_file(public_path(...))` — `System/Config/Assets.php` tetap 1 baris `Add::link('main.css'...)` tapi output jadi `main.css?v=1716160000` tanpa edit manual

### Examples

**`System/Config/Assets.php` (real usage):**
```php
Add::meta('charset="utf-8"');
Add::meta('name="viewport"', 'width=device-width, initial-scale=1');
Add::link('main.css', 'stylesheet', 'text/css');
Add::link('favicon.png', 'shortcut icon');
Add::script('config/system.js', 'text/javascript', 'UTF-8');
Add::script('main.js', 'text/javascript', 'UTF-8');
```

**Views/Templates (`System/Apps/Templates/Header.php`):**
```php
use System\Core\NSY_AssetManager as Add;
?>
<head>
  <?php header_assets(); // echoes Assets.php via Load::template ?>
  <?php Add::script('custom.js', 'text/javascript', '', ['async' => 'async']); ?>
</head>
```

### Security Notes

- `meta` content and all `link`/`script` attributes are escaped via `htmlspecialchars(ENT_QUOTES, UTF-8)` — prevents XSS via `$title` etc.
- `custom()` is raw — never pass user input without escaping
- `isAbsoluteUrl()` handles `http://`, `https://`, `//` — prevents double `css_url()` on CDN URLs

---

## Troubleshooting

- **Wrong asset URL:** Check `config_app('public_dir')`, `css_dir`/`js_dir`/`img_dir`, and `base_url()` (depends on `$_SERVER['HTTP_HOST']` + `APP_DIR`)
- **View not found:** Ensure `$filename` without `.php`, and `get_mvc_view_dir()` / `get_hmvc_view_dir()` point correctly
- **Undefined constant:** Should not happen — helpers use `defined()` guard; verify `NSY_System` booted (`new NSY_System()` in `public/index.php`)

## Quick Reference

| Method | Purpose | Returns |
|---|---|---|
| `Load::view($module,$file,$vars)` | Render MVC/HMVC view | `void` |
| `Load::template($file,$vars)` | Render template | `void` |
| `Load::model($fqcn)` | Instantiate model | `object` |
| `Add::meta($attr,$content)` | Echo `<meta>` | `bool` |
| `Add::link($file,$rel,$type,$title)` | Echo `<link>` | `bool` |
| `Add::script($file,$type,$charset,$attr)` | Echo `<script>` | `bool` |
| `Add::custom($html)` | Echo raw HTML | `bool` |

Related: `System/Core/Load.php`, `System/Core/NSY_AssetManager.php:10`, `System/Core/NSY_Helpers_Global.php` (`base_url`, `css_url` etc.), `System/Config/Assets.php`.
