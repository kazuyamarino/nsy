# NSY Global Helpers (System/Core/NSY_Helpers_Global.php)

Documentation for NSY Framework global helper functions. This document intentionally does NOT cover configuration functions (`config_app()`, `config_env()`, `config_db()`, `config_site()`).

All functions here are available as global functions (no namespace) after the NSY bootstrap runs.

## Table of Contents

1. [Variable Checking](#variable-checking)
2. [URI & Path Helpers](#uri--path-helpers)
3. [Asset URL Helpers (Safe against undefined constants)](#asset-url-helpers-safe-against-undefined-constants)
4. [NSY System Constant Getters (with config fallback)](#nsy-system-constant-getters-with-config-fallback)
5. [HTTP & Input Helpers](#http--input-helpers)
6. [Data Conversion & JSON](#data-conversion--json)
7. [Array & Number Utilities](#array--number-utilities)
8. [String & Media Utilities](#string--media-utilities)
9. [Generator & Client Info](#generator--client-info)
10. [Aurora Data Export](#aurora-data-export)
11. [Practical Examples](#practical-examples)
12. [Security & Stability Notes](#security--stability-notes)

## Variable Checking

- not_filled($str): bool
  - True if the variable is not filled/empty (array/string).
- is_filled($str): bool
  - The inverse of `not_filled()`. True if the variable has a value.

## URI & Path Helpers

- base_url($url = ''): string
  - Returns the base URL with an optional `$url` path appended.
- assets_url($url = ''): string
  - URL to `public_dir/assets/` + `$url`.
- public_path($url = ''): string
  - Filesystem full path to the public directory. Respects `public_dir` if set.
- redirect_url($url): void
  - Send Location header to `$url` (absolute/relative). Stops execution.
- redirect($url): void
  - Send Location header to `base_url($url)`. Stops execution.
- redirect_back(): void
  - Redirect back to `$_SERVER['HTTP_REFERER']`. Stops execution.

- get_uri_segment($index): string
  - Get URI segment by index (0-based on the path). Errors if not present.
- get_last_uri_segment(): string
  - The last segment of the current request path.
- get_uri(): string
  - The full request path (excluding query string).

## Asset URL Helpers (Safe against undefined constants)

- nsy_resolve_asset_dir($key): string
  - Resolves the base URL for an asset directory using constants `IMG_DIR|JS_DIR|CSS_DIR` when available.
  - Fallback: `base_url()` + `public_dir` + `assets/` + configured directory (`img_dir|js_dir|css_dir`).
- img_url($url = ''): string
  - URL to the image directory + `$url`.
- js_url($url = ''): string
  - URL to the JavaScript directory + `$url`.
- css_url($url = ''): string
  - URL to the CSS directory + `$url`.

Example:
```php
<link rel="stylesheet" href="<?= css_url('main.css') ?>">
<script src="<?= js_url('app.js') ?>"></script>
<img src="<?= img_url('logo.png') ?>" alt="Logo">
```

## NSY System Constant Getters (with config fallback)

- get_version(): string
- get_codename(): string
- get_lang_code(): string
- get_og_prefix(): string
- get_title(): string
- get_desc(): string
- get_keywords(): string
- get_author(): string
- get_session_prefix(): string
- get_site_email(): string
- get_vendor_dir(): string (ends with '/')
- get_mvc_view_dir(): string (ends with '/')
- get_hmvc_view_dir(): string (ends with '/')
- get_system_tmp_dir(): string (ends with '/')

Note: Each function will use constants defined by `NSY_System` if available (via `defined()/constant()`), otherwise falls back to configuration values.

## HTTP & Input Helpers

- post($name)
  - Returns `$_POST[$name]` or `null`. Errors if `$name` is empty.
- get($name)
  - Returns `$_GET[$name]` or `null`. Errors if `$name` is empty.
- array_items($param, $param2 = '', $param3 = 0)
  - Helper for accessing nested structures in `$_FILES`. Supports chained access like `$_FILES[$param][$param2][$param3]`.

## Data Conversion & JSON

- fetch_json(array $data, int $status = 0): string
  - Sets HTTP status and returns a JSON string from `$data`.
- fetch_raw_json(string $variable = '')
  - Reads `php://input`, decodes JSON to an array, then returns `$array[$variable]`.

## Array & Number Utilities

- array_flatten($items): array
  - Flattens a multi-dimensional array into a single dimension.
- number_format_short(int|float $n, int $precision = 1): string
  - Short number formatting for large values: `Rb` (thousand), `Jt` (million), `M` (billion), `T` (trillion).
- sequence(string $bind, iterable $variables): array
  - Helps build named placeholders for SQL IN queries.
  - Returns: `[$in, $in_params]`, e.g., `:id0,:id1` and the pair array `[':id0'=>..., ':id1'=>...]`.

## String & Media Utilities

- string_encrypt($action = 'encrypt', $string = ''): string
  - Simple encrypt/decrypt (AES-256-CBC). Replace `$secret_key` and `$secret_iv` in your implementation for production use.
- image_to_base64($files): array
  - Convert uploaded files (from `$_FILES['field']`) to base64 + data URL.
- string_to_base64($string, $ext = 'jpg'): array
  - Convert a binary image string to base64 + data URL with the given extension.

## Generator & Client Info

- generate_num($prefix = 'NSY-', $id_length = 6, $num_length = 10)
  - Generate a random identifier with a prefix.
- get_ua(): array
  - User-Agent info: `name`, `version`, `platform`, `userAgent`.

## Aurora Data Export

- aurora($ext, $name, $sep, array $header, array $data, $s)
  - Export tabular data (txt, csv, xls, xlsx, ods) with custom separators and delimiters.

## Practical Examples

```php
// Redirect to login page
redirect('login');

// Get the second URI segment
$second = get_uri_segment(2);

// Build asset URLs
$style = css_url('themes/dark.css');
$logo  = img_url('brand/logo.svg');

// JSON response
http_response_code(200);
echo fetch_json(['ok' => true], 200);
```

## Security & Stability Notes

- Asset helpers (`img_url/js_url/css_url`) and system constants are safe against undefined constants via `defined()/constant()` checks with config fallbacks.
- Avoid using `string_encrypt()` for high-security scenarios without reviewing/resetting the key/IV and adopting modern cryptography practices.
