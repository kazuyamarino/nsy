# NSY Global Helpers (System/Core/NSY_Helpers_Global.php)

Documentation for NSY Framework global helper functions.

All functions here are available as **global functions** (no namespace) after the NSY Framework boots (`System/Vendor/autoload.php` + `NSY_System`). No import is needed — just call them.

## Table of Contents

1. [Variable Checking](#1-variable-checking)
2. [URI & Path Helpers](#2-uri--path-helpers)
3. [Asset URL Helpers](#3-asset-url-helpers)
4. [NSY System Constant Getters](#4-nsy-system-constant-getters)
5. [HTTP & Input Helpers](#5-http--input-helpers)
6. [Data Conversion & JSON](#6-data-conversion--json)
7. [Array & Number Utilities](#7-array--number-utilities)
8. [String & Media Utilities](#8-string--media-utilities)
9. [Generator & Client Info](#9-generator--client-info)
10. [Aurora Data Export](#10-aurora-data-export)
11. [Practical Examples](#11-practical-examples)
12. [Security & Stability Notes](#12-security--stability-notes)
13. [Quick Reference](#quick-reference)

---

## 1. Variable Checking

```php
not_filled($value): bool
is_filled($value): bool   // inverse of not_filled
```

True when a value is missing, empty, or — for strings — whitespace-only. Arrays are empty when `[]`.

```php
not_filled('');      // true
not_filled('   ');   // true  (whitespace-only)
not_filled([]);      // true
not_filled(null);    // true
is_filled('hello');  // true
is_filled(['a']);    // true

// 0 and "0" are considered not filled (PHP's empty semantics)
not_filled(0);       // true
not_filled('0');     // true
```

> Correlated: used throughout `System/Core/NSY_DB.php` and validation helpers.

---

## 2. URI & Path Helpers

```php
base_url(string $url = ''): string
assets_url(string $url = ''): string
public_path(string $url = ''): string
redirect_url(string $url): void   // header Location: $url + exit
redirect(string $url): void       // header Location: base_url($url) + exit
redirect_back(): void             // header Location: HTTP_REFERER or base_url() + exit
get_uri_segment(int $key = ''): string
get_last_uri_segment(): string
get_uri(): string
```

```php
// Base URL respects app_dir and scheme (HTTPS via $_SERVER['HTTPS'] or port 443)
base_url('login');          // "https://example.id/nsy/login"
assets_url('css/app.css');  // "https://example.id/nsy/public/assets/css/app.css"
public_path('uploads/a.jpg'); // "/var/www/html/public/uploads/a.jpg"

$_SERVER['REQUEST_URI'] = '/a/b/c?x=1';
get_uri();               // "/a/b/c"
get_uri_segment(2);      // "b"  (0 => "", 1 => "a", 2 => "b")
get_last_uri_segment();  // "c"
```

> All three URI helpers safely fall back when `$_SERVER` keys are missing (e.g. CLI). `redirect_back()` falls back to `base_url()` when `HTTP_REFERER` is absent.

---

## 3. Asset URL Helpers

```php
nsy_resolve_asset_dir(string $key): string  // key: img_dir|js_dir|css_dir
img_url(string $url = ''): string
js_url(string $url = ''): string
css_url(string $url = ''): string
```

Prefer constants `IMG_DIR|JS_DIR|CSS_DIR` (defined by `NSY_System`) when available; otherwise build from `base_url()` + `public_dir` + config directory.

```php
<link rel="stylesheet" href="<?= css_url('main.css') ?>">
<script src="<?= js_url('app.js') ?>"></script>
<img src="<?= img_url('logo.png') ?>" alt="Logo">
```

---

## 4. NSY System Constant Getters

Each tries `defined()/constant()` first (set by `NSY_System`), then falls back to config.

```php
get_version(): string        // VERSION or config_site('version')
get_codename(): string       // CODENAME or config_site('codename')
get_lang_code(?string $name = null): string|false  // locale or lookup
get_og_prefix(): string
get_title(): string
get_desc(): string
get_keywords(): string
get_author(): string
get_session_prefix(): string
get_site_email(): string
get_vendor_dir(): string     // ends with '/'
get_mvc_view_dir(): string   // ends with '/'
get_hmvc_view_dir(): string  // ends with '/'
get_system_tmp_dir(): string // ends with '/'
```

```php
get_lang_code();           // "id-ID" — app locale
get_lang_code('Spanish');  // "es"   — name lookup (see Part B of README_LIBRARIES.md)
```

> Correlated: `System/Core/NSY_System.php` defines these constants at boot.

---

## 5. HTTP & Input Helpers

```php
post(string $param): mixed
get(string $param): mixed
array_items(string $param, string $param2 = '', int $param3 = 0): mixed
```

```php
// Returns $_POST[$name] / $_GET[$name] or null; errors when $param is empty
$username = post('username');
$page     = get('page');

// Nested $_FILES access
$tmp = array_items('avatar', 'tmp_name');          // $_FILES['avatar']['tmp_name']
$tmp = array_items('gallery', 'tmp_name', 2);      // $_FILES['gallery']['tmp_name'][2]
```

> Errors use `NSY_Desk::static_error_handler()` — consistent with the framework.

---

## 6. Data Conversion & JSON

```php
fetch_json(array $data = [], int $status = 0): string
fetch_raw_json(string $variable = ''): mixed
```

```php
// Encode + set HTTP status
http_response_code(200);
echo fetch_json(['ok' => true], 200); // '{"ok":true}'

// Read php://input as array; optional key
$body = fetch_raw_json();        // whole array, or [] when empty/invalid
$name = fetch_raw_json('name');  // $array['name'] ?? null
```

> `fetch_raw_json('')` returns the full array; invalid JSON now returns `[]`/`null` instead of a warning.

---

## 7. Array & Number Utilities

```php
array_flatten(mixed $items): array
number_format_short(int|float $n, int $precision = 1): string
sequence(string $bind, iterable $variables): array  // [$in, $params]
```

```php
array_flatten([1, [2, [3]]]); // [1, 2, 3]

number_format_short(1500);        // "1.5 Rb"
number_format_short(2500000);     // "2.5 Jt"
number_format_short(1500000000);  // "1.5 M"
number_format_short(999, 0);      // "999"

// SQL IN placeholders
[$in, $params] = sequence(':id', [10, 20, 30]);
// $in => ":id0,:id1,:id2"  $params => [":id0"=>10, ":id1"=>20, ":id2"=>30]
```

> `sequence()` now validates `$bind` and `$variables` strictly; `$in_params` is always initialized.

---

## 8. String & Media Utilities

```php
string_encrypt(string $action = 'encrypt', string $string = ''): string|false
image_to_base64(array $files): array  // $files = $_FILES['field']
string_to_base64(string $string, string $ext = 'jpg'): array
```

```php
$enc = string_encrypt('encrypt', 'secret');
$dec = string_encrypt('decrypt', $enc);

$info = image_to_base64($_FILES['photo']);
// ['name','type','base64','dataUrl' => 'data:image/jpeg;base64,...']

$info = string_to_base64($binary, 'png');
// ['base64','dataUrl' => 'data:image/png;base64,...']
```

> Replace `$secret_key` / `$secret_iv` in `string_encrypt()` before production.

---

## 9. Generator & Client Info

```php
generate_num(string $prefix = 'NSY-', int $id_length = 6, int $num_length = 10): string
get_ua(): array  // ['name','version','platform','userAgent','pattern']
```

```php
generate_num();              // "NSY-482917" (padded to num_length)
generate_num('INV-', 8, 12); // "    INV-00482011"

$ua = get_ua();
echo $ua['name'];     // "Google Chrome"
echo $ua['platform']; // "Windows"
```

> `generate_num()` is now typed and handles edge values safely. `get_ua()` no longer emits warnings when `HTTP_USER_AGENT` is missing or unknown.

---

## 10. Aurora Data Export

```php
aurora(string $ext, string $name, string $sep, array $header, array $data, string $s): true
```

Exports `txt|csv|xls|xlsx|ods` with a chosen separator and string delimiter.

```php
aurora(
    'csv', 'report', 'comma',
    ['Name', 'Age'],
    [['Alice', 30], ['Bob', 25]],
    'double'   // 'double' | 'single' | null
);
// writes report.csv with "Name","Age" header
```

Separators: `tab|comma|semicolon|space|dot|pipe`. Delimiters: `double|single`.

---

## 11. Practical Examples

```php
// Redirect to login
redirect('login');

// Second URI segment
$second = get_uri_segment(2);

// Asset URLs
$style = css_url('themes/dark.css');
$logo  = img_url('brand/logo.svg');

// JSON response
echo fetch_json(['ok' => true], 200);

// SQL IN query
[$in, $params] = sequence(':id', $ids);
$db->query("SELECT * FROM users WHERE id IN ($in)", $params);
```

---

## 12. Security & Stability Notes

- **Variable checking** now trims whitespace — `"   "` is correctly treated as empty.
- **URI/asset helpers** are safe against missing `$_SERVER` keys (no warnings in CLI) and now deduplicate scheme/host logic.
- **Asset helpers** (`img_url/js_url/css_url`) stay safe against undefined constants via `defined()/constant()` with config fallbacks.
- **Aurora** validates every argument before writing.
- Avoid `string_encrypt()` for high-security use without rotating the key/IV and reviewing the AES-CBC usage.

---

## Quick Reference

| Function | Purpose | Returns |
|---|---|---|
| `not_filled($v)` / `is_filled($v)` | Empty / whitespace check | `bool` |
| `base_url($u)` | Base URL + path | `string` |
| `assets_url($u)` | Assets URL + path | `string` |
| `public_path($u)` | Filesystem public path | `string` |
| `nsy_resolve_asset_dir($k)` | Resolve IMG/JS/CSS base URL | `string` |
| `img_url/js_url/css_url($u)` | Asset directory URL | `string` |
| `redirect_url($u)` / `redirect($u)` / `redirect_back()` | HTTP redirect + exit | `void` |
| `get_uri()` / `get_uri_segment($i)` / `get_last_uri_segment()` | Current request URI | `string` |
| `get_version()` etc. (14 getters) | System constants with config fallback | `string` |
| `post($k)` / `get($k)` / `array_items(...)` | Superglobal access | `mixed` |
| `fetch_json($d,$s)` | JSON encode + status | `string` |
| `fetch_raw_json($k)` | php://input JSON decode | `mixed` |
| `array_flatten($a)` | Flatten nested array | `array` |
| `number_format_short($n,$p)` | Short number (Rb/Jt/M/T) | `string` |
| `sequence($bind,$vars)` | SQL IN placeholders | `array` |
| `string_encrypt($a,$s)` | AES-256-CBC encrypt/decrypt | `string\|false` |
| `image_to_base64($f)` / `string_to_base64($s,$e)` | Base64 + data URL | `array` |
| `generate_num($pre,$id,$num)` | Random prefixed ID | `string` |
| `get_ua()` | User-Agent parsing | `array` |
| `aurora($ext,$name,$sep,$h,$d,$s)` | Tabular export | `true` |

Related source: `System/Core/NSY_Helpers_Global.php`, correlated: `System/Core/NSY_System.php`, `System/Config/App.php`, `System/Config/Site.php`, `env.php`.
