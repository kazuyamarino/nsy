# NSY Global Helpers (System/Core/NSY_Helpers_Global.php)

Documentation for NSY Framework global helper functions.

All functions here are available as **global functions** (no namespace) after the NSY Framework boots (`System/Vendor/autoload.php` + `NSY_System`). No import is needed — just call them.

## Table of Contents

1. [Variable Checking](#variable-checking)
2. [URI & Path Helpers](#uri--path-helpers)
3. [Asset URL Helpers](#asset-url-helpers)
4. [NSY System Constant Getters](#nsy-system-constant-getters)
5. [Configuration Getters](#configuration-getters)
6. [HTTP & Input Helpers](#http--input-helpers)
7. [Data Conversion & JSON](#data-conversion--json)
8. [Array & Number Utilities](#array--number-utilities)
9. [String & Media Utilities](#string--media-utilities)
10. [Generator & Client Info](#generator--client-info)
11. [Date Helpers](#date-helpers)
12. [Aurora Data Export](#aurora-data-export)
13. [Practical Examples](#practical-examples)
14. [Security & Stability Notes](#security--stability-notes)
15. [Quick Reference](#quick-reference)

---

## Variable Checking

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

## URI & Path Helpers

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

## Asset URL Helpers

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

## NSY System Constant Getters

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
get_repo_url(): string       // REPO_URL or config_site('repo_url')
get_since(): string          // SINCE_YEAR or config_site('since')
get_vendor_dir(): string     // ends with '/'
get_mvc_view_dir(): string   // ends with '/'
get_hmvc_view_dir(): string  // ends with '/'
get_system_tmp_dir(): string // ends with '/'
```

```php
get_lang_code();           // "id-ID" — app locale
get_lang_code('Spanish');  // "es"   — name lookup (see Part B of README_LIBRARIES.md)
get_repo_url();            // "https://github.com/..."  (footer / docs link)
get_since();               // "2019"  — copyright range start
```

> Correlated: `System/Core/NSY_System.php` defines these constants at boot.

---

## Configuration Getters

Read values from `System/Config/App.php`, `System/Config/Site.php` and the root
`env.php` without `include`-ing them yourself.

```php
config_app(string $key): mixed                     // System/Config/App.php
config_site(string $key): mixed                    // System/Config/Site.php
config_env(string $key, string $sub = ''): mixed   // env.php
config_db(string $conn = '', string $key = ''): mixed
```

```php
config_app('app_env');                // "development"
config_app('vendor_dir');             // "System/Vendor/"
config_site('sitetitle');             // "NSY PHP Framework"
config_env('APP_DIR');                // "nsy"
config_env('DB_CONNECTION', 'host');  // env.php['DB_CONNECTION']['host']
config_db();                          // the whole ['connections' => [...]]
config_db('primary', 'database');     // env.php['connections']['primary']['database']
```

- `config_app()` / `config_site()` return `null` for an unknown key.
- `config_env($key)` returns the whole env value; pass `$sub` to read one child key.
- `config_db()` with no (or partial) arguments returns the entire `connections`
  array; pass both `$conn` and `$key` for a single DSN value.
- The App/Site keys and matching `env.php` variables are listed in
  [`OVERVIEW.md`](OVERVIEW.md#framework-configuration), [`README_DEPLOY_HOSTING.md`](README_DEPLOY_HOSTING.md)
  and [`README_MODEL.md`](README_MODEL.md).

---

## HTTP & Input Helpers

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

> Errors use `NSY_Desk::staticErrorHandler()` — consistent with the framework.

---

## Data Conversion & JSON

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

## Array & Number Utilities

```php
array_flatten(mixed $items): array
number_format_short(int|float $n, int $precision = 1): string
sequence(string $bind, iterable $variables): array  // [$in, $params]
terner(mixed $condition, mixed $if_true, mixed $if_false): mixed
```

```php
array_flatten([1, [2, [3]]]); // [1, 2, 3]

terner($user, $user->name, 'Guest'); // "Guest" when $user is empty
terner(true, 'yes', 'no');           // "yes"

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

## String & Media Utilities

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

## Generator & Client Info

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

## Date Helpers

```php
get_today(): string   // e.g. "Wednesday, 30 September 2026" (Carbon isoFormat)
get_year(): string    // "2026"
```

```php
echo get_today();  // header / footer date
echo get_year();   // end of the copyright range: get_since() - get_year()
```

> Both use `nesbot/carbon`; the date format follows the app locale. Templates call
> them directly so a controller that forgets to pass a date cannot blank the footer.

---

## Aurora Data Export

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

## Practical Examples

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

## Security & Stability Notes

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
| `get_version()` etc. (16 getters) | System constants with config fallback | `string` |
| `config_app($k)` / `config_site($k)` | Read `App.php` / `Site.php` key | `mixed` |
| `config_env($k,$sub)` / `config_db($c,$k)` | Read `env.php` / a DSN value | `mixed` |
| `post($k)` / `get($k)` / `array_items(...)` | Superglobal access | `mixed` |
| `fetch_json($d,$s)` | JSON encode + status | `string` |
| `fetch_raw_json($k)` | php://input JSON decode | `mixed` |
| `array_flatten($a)` | Flatten nested array | `array` |
| `number_format_short($n,$p)` | Short number (Rb/Jt/M/T) | `string` |
| `sequence($bind,$vars)` | SQL IN placeholders | `array` |
| `terner($c,$a,$b)` | Inline ternary selector | `mixed` |
| `qb($t,$alias,$conn)` | Query Builder factory ([guide](README_QUERY_BUILDER.md)) | `NSY_QueryBuilder` |
| `string_encrypt($a,$s)` | AES-256-CBC encrypt/decrypt | `string\|false` |
| `image_to_base64($f)` / `string_to_base64($s,$e)` | Base64 + data URL | `array` |
| `generate_num($pre,$id,$num)` | Random prefixed ID | `string` |
| `get_ua()` | User-Agent parsing | `array` |
| `get_today()` / `get_year()` | Current date / year (Carbon) | `string` |
| `aurora($ext,$name,$sep,$h,$d,$s)` | Tabular export | `true` |

Related source: `System/Core/NSY_Helpers_Global.php`, correlated: `System/Core/NSY_System.php`, `System/Config/App.php`, `System/Config/Site.php`, `env.php`.
