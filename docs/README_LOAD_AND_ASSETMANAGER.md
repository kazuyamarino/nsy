# NSY Load & Asset Manager Usage Guide

This guide documents how to use the core loader (`System/Core/Load.php`) and asset manager (`System/Core/NSY_AssetManager.php`) in NSY Framework.

- File: `System/Core/Load.php` (class `System\Core\Load`)
- File: `System/Core/NSY_AssetManager.php` (class `System\Core\NSY_AssetManager`)

Both components are designed to be simple, safe, and consistent with NSY's helper functions and configuration.
 
## Table of Contents

1. [Load (Render Views/Templates & Instantiate Models)](#load-render-viewstemplates--instantiate-models)
   - [Methods](#methods)
   - [Usage Examples](#usage-examples)
2. [NSY_AssetManager (Generate Meta/Link/Script/Custom Tags)](#nsy_assetmanager-generate-metalinkscriptcustom-tags)
   - [Methods](#methods-1)
   - [Namespacing & Aliasing](#namespacing--aliasing)
   - [How URLs Are Resolved](#how-urls-are-resolved)
3. [Troubleshooting](#troubleshooting)
4. [Version Notes](#version-notes)

## Load (Render Views/Templates & Instantiate Models)

- Purpose: Render MVC/HMVC views and system templates through the Razr engine, and instantiate model classes.
- Engine: Lazily initializes a shared Razr `Engine` with `FilesystemLoader(get_vendor_dir())`.

### Methods

- `Load::view($module, $filename, $vars = [])`
  - Renders a view using Razr.
  - If `$module` is empty/null, renders MVC view from `get_mvc_view_dir()`.
  - If `$module` is non-empty, renders HMVC view from `get_hmvc_view_dir() . $module . '/Views/'`.
  - `$filename` is view filename without extension (".php" will be added).
  - `$vars` can be array or object. Shows an error via `NSY_Desk::static_error_handler()` for invalid inputs.

- `Load::template($filename, $vars = [])`
  - Renders a template from `get_system_tmp_dir()` using Razr.
  - `$filename` is template filename without extension (".php" will be added).
  - `$vars` can be array or object. Validated like `view()`.

- `Load::model($fullclass)`
  - Instantiates a model by fully-qualified class name string.
  - Validates the class exists; otherwise throws an error via `NSY_Desk::static_error_handler()`.

Notes:
- Directory helpers `get_mvc_view_dir()`, `get_hmvc_view_dir()`, and `get_system_tmp_dir()` are safe and use `defined()/constant()` with config fallbacks (see `System/Core/NSY_Helpers_Global.php`).

### Usage Examples

- MVC view rendering in a controller:

```php
<?php
namespace System\Apps\General\Controllers;

use System\Core\Load;

class Controller_Welcome
{
    public function index()
    {
        $data = ['title' => get_title()];
        Load::template('Header', $data);              // Apps/Templates/Header.php
        Load::view(null, 'Index_Welcome', $data);     // Apps/General/Views/Index_Welcome.php
        Load::template('Footer', $data);              // Apps/Templates/Footer.php
    }
}
```

- HMVC view rendering:

```php
use System\Core\Load;

$vars = ['title' => 'Hello'];
Load::template('Header', $vars);
Load::view('HMVC', 'Index_Hello', $vars);  // Apps/Modules/HMVC/Views/Index_Hello.php
Load::template('Footer', $vars);
```

- Instantiating a model by FQCN:

```php
use System\Core\Load;

// Using ::class or a fully qualified class name string
$userModel = Load::model(\App\Models\User::class);
```

## NSY_AssetManager (Generate Meta/Link/Script/Custom Tags)

- Purpose: Echo HTML tags for metadata and assets with safe URL resolution.
- Consolidation: Uses global helpers `css_url()`, `js_url()`, and `img_url()` for asset paths. These helpers resolve constants (`CSS_DIR`, `JS_DIR`, `IMG_DIR`) with `defined()/constant()` checks and fallback to config.

### Methods

- `NSY_AssetManager::meta($attr, $content = '')`
  - Echoes `<meta ...>`.
  - If `$content` empty, outputs `<meta {$attr}>`. If both provided, outputs `<meta {$attr} content="{$content}">`.

- `NSY_AssetManager::link($filename, $rel, $type = '', $title = '')`
  - Echoes `<link ...>`.
  - If `$filename` is an absolute URL (`http://`, `https://`, `//`), uses it directly.
  - If `$rel === 'stylesheet'`, href comes from `css_url($filename)`.
  - If `$rel === 'shortcut icon'`, href comes from `img_url($filename)`.
  - Otherwise, shows an error via `NSY_Desk::static_error_handler()`.

- `NSY_AssetManager::script($filename, $type = '', $charset = '', $attr = '')`
  - Echoes `<script ...>`.
  - If `$filename` is absolute, uses it directly; otherwise uses `js_url($filename)`.
  - `$attr` can be array or string. Array keys are sanitized and values are escaped.

- `NSY_AssetManager::custom($values)`
  - Echoes raw `$values` as-is (use with caution).

### Namespacing & Aliasing

- Fully qualified calls:

```php
\System\Core\NSY_AssetManager::link('main.css', 'stylesheet', 'text/css');
\System\Core\NSY_AssetManager::script('app.js', 'text/javascript');
```

- Recommended alias for conciseness (often referred to as `Add` in examples):

```php
use System\Core\NSY_AssetManager as Add;

Add::meta('charset="utf-8"');
Add::link('main.css', 'stylesheet', 'text/css');
Add::script('app.js', 'text/javascript');
Add::link('favicon.ico', 'shortcut icon', 'image/x-icon', 'Site Favicon');
```

- Using absolute URLs (CDN):

```php
use System\Core\NSY_AssetManager as Add;

Add::link('https://cdn.example.com/css/lib.min.css', 'stylesheet');
Add::script('https://cdn.example.com/js/lib.min.js');
```

### How URLs Are Resolved

- The helpers `css_url()`, `js_url()`, `img_url()` determine the final URL by:
  - Using constants `CSS_DIR`, `JS_DIR`, `IMG_DIR` if defined by `NSY_System`.
  - Otherwise falling back to `base_url()`, `config_app('public_dir')`, and `config_app('css_dir'|'js_dir'|'img_dir')`.
- This prevents undefined constant errors and keeps behavior consistent across the framework.

## Troubleshooting

- If asset URLs look incorrect, verify:
  - `base_url()` and `public_dir` in `config_app()`.
  - Asset directory config values (`css_dir`, `js_dir`, `img_dir`).
  - That `NSY_System` correctly defines constants, or that the config fallback is correct.

- If view/template rendering fails:
  - Ensure `get_mvc_view_dir()`, `get_hmvc_view_dir()`, and `get_system_tmp_dir()` resolve to the expected directories.
  - Check that view/template files exist and `$filename` is passed without the `.php` extension.
  - Confirm `$vars` is an array or object.

## Version Notes

- As of this guide, `NSY_AssetManager` has been consolidated to use global helpers for asset directory resolution to eliminate undefined constant errors and centralize logic.
