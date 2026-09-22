# NSY File Library — User Tutorial

A practical guide to the file-management library at `System/Libraries/File.php`
(namespace `System\Libraries`, class `File`). All methods are **static**, so no
instantiation is needed.

## Table of Contents

**Part A — [File Library](#nsy-file-library-user-tutorial)**

1. [Getting Started](#1-getting-started)
2. [Checking Existence — `exists()`](#2-checking-existence-exists)
3. [Writing Files — `writeFile()`](#3-writing-files-writefile)
4. [Deleting a File — `delete()`](#4-deleting-a-file-delete)
5. [Creating Directories — `createDir()`](#5-creating-directories-createdir)
6. [Copying a Directory Tree — `copyDirRecursively()`](#6-copying-a-directory-tree-copydirrecursively)
7. [Deleting Directories](#7-deleting-directories)
8. [Iterating a Directory — `getFilesFromDir()`](#8-iterating-a-directory-getfilesfromdir)
9. [Listing Filenames Recursively — `getFilenames()`](#9-listing-filenames-recursively-getfilenames)
10. [Directory Report — `getDirFileInfo()`](#10-directory-report-getdirfileinfo)
11. [Single File Report — `getFileInfo()`](#11-single-file-report-getfileinfo)
12. [MIME Type by Extension — `getMimeByExtension()`](#12-mime-type-by-extension-getmimebyextension)
13. [Complete Example — Upload Handler](#13-complete-example-simple-upload-handler-sketch)
14. [File Quick Reference](#file-quick-reference)

**Part B — [LanguageCode Library](#nsy-languagecode-library-user-tutorial)**

1. [Getting Started](#1-getting-started-with-languagecode)
2. [List Every Language — `get()`](#2-list-every-language-get)
3. [Name from Code — `getLanguageFromCode()`](#3-name-from-code-getlanguagefromcode)
4. [Code from Name — `getCodeFromLanguage()`](#4-code-from-name-getcodefromlanguage)
5. [Application Helpers](#5-application-helpers)
6. [Complete Example — Language Dropdown](#6-complete-example-language-dropdown)
7. [LanguageCode Quick Reference](#languagecode-quick-reference)

**Part C — [LoadTime Library](#nsy-loadtime-library-user-tutorial)**

1. [Measuring Execution Time](#1-measuring-execution-time)
2. [Usage](#2-usage)
3. [LoadTime Quick Reference](#loadtime-quick-reference)

**Part D — [Request Library](#nsy-request-library-user-tutorial)**

1. [Which Method Is This?](#1-which-method-is-this)
2. [Reading Input — `input()`](#2-reading-input-input)
3. [Sanitizing to Array / Object / JSON](#3-sanitizing-to-array-object-json)
4. [Sanitizing Single Values](#4-sanitizing-single-values)
5. [Content Type](#5-content-type)
6. [One-Shot Readers (via Input)](#6-one-shot-readers-via-input)
7. [Request Quick Reference](#request-quick-reference)

**Part E — [Validate Library](#nsy-validate-library-user-tutorial)**

1. [Sanitizing Whole Values](#1-sanitizing-whole-values)
2. [Strings, Integers, Floats, Booleans](#2-strings-integers-floats-booleans)
3. [IP, URL and Email](#3-ip-url-and-email)
4. [Validate Quick Reference](#validate-quick-reference)

---

## 1. Getting Started

Import the class, then call any method statically:

```php
use System\Libraries\File;

if (File::exists('/var/www/html/storage/report.pdf')) {
    echo 'Found it!';
}
```

> The class is PSR-4 autoloaded (`System\` → `System/`), so it works anywhere
> after `System/Vendor/autoload.php` is loaded — controllers, models, routes,
> CLI scripts.

---

## 2. Checking Existence — `exists()`

Works with local paths **and** URLs.

```php
// Local file → true only if the path is an actual file (not a directory)
File::exists('/var/www/html/storage/report.pdf'); // bool

// Remote URL → performs a HEAD request, true for HTTP 2xx–3xx
File::exists('https://example.com/logo.png');     // bool
```

Signature: `exists(string $file): bool`

---

## 3. Writing Files — `writeFile()`

Creates the file if missing, overwrites otherwise. Uses an exclusive lock
(`LOCK_EX`), so concurrent writes are safe.

```php
$ok = File::writeFile('/var/www/html/storage/hello.txt', "Hello, NSY!\n");

if (!$ok) {
    echo 'Could not write the file (check directory permissions).';
}
```

Append mode instead of overwrite:

```php
File::writeFile('/var/www/html/storage/app.log', "new line\n", 'ab');
```

Signature: `writeFile(string $path, string $data, string $mode = 'wb'): bool`

---

## 4. Deleting a File — `delete()`

Returns `false` when the file does not exist (nothing to delete).

```php
if (File::delete('/var/www/html/storage/old-report.pdf')) {
    echo 'Deleted.';
} else {
    echo 'File was already gone.';
}
```

Signature: `delete(string $file): bool`

---

## 5. Creating Directories — `createDir()`

Creates nested directories recursively (`mkdir(..., 0777, true)`).

```php
File::createDir('/var/www/html/storage/uploads/2026/09');
```

> Returns `false` if the directory **already exists** — that is the normal
> "nothing to do" signal, not an error. Check with `is_dir()` first if you
> need to tell the two cases apart.

Signature: `createDir(string $path): bool`

---

## 6. Copying a Directory Tree — `copyDirRecursively()`

Copies all files **including subdirectories** from one place to another.

```php
$ok = File::copyDirRecursively(
    '/var/www/html/storage/templates',
    '/var/www/html/storage/templates-backup'
);

if (!$ok) {
    echo 'Copy failed partway — check permissions and disk space.';
}
```

Signature: `copyDirRecursively(string $from, string $to): bool`

---

## 7. Deleting Directories

Two methods, pick the right one:

```php
// Removes a directory ONLY if it is empty
File::deleteEmptyDir('/var/www/html/storage/empty-folder'); // bool

// Removes a directory WITH everything inside it (files + subfolders)
File::deleteDirRecursively('/var/www/html/storage/cache');   // bool
```

> `deleteDirRecursively()` also removes the top-level directory itself, and
> returns `false` if the path is not a directory.

Signatures:

```php
File::deleteEmptyDir(string $path): bool
File::deleteDirRecursively(string $path): bool
```

---

## 8. Iterating a Directory — `getFilesFromDir()`

Returns a `\DirectoryIterator` for manual loops, or `false` if the path is
not a directory.

```php
$iterator = File::getFilesFromDir('/var/www/html/storage');

if ($iterator !== false) {
    foreach ($iterator as $item) {
        if ($item->isDot()) {
            continue; // skip "." and ".."
        }
        echo $item->getFilename() . PHP_EOL;
    }
}
```

Signature: `getFilesFromDir(string $path): object|false`

---

## 9. Listing Filenames Recursively — `getFilenames()`

Returns a flat array of every filename under a directory (subfolders
included, dot-files skipped).

```php
// Plain filenames
$names = File::getFilenames('/var/www/html/storage');
print_r($names);
// Array ( [0] => report.pdf [1] => hello.txt ... )

// With full paths
$paths = File::getFilenames('/var/www/html/storage', true);
```

> The third parameter (`$_recursion`) is for internal use — never pass it.
> Returns `false` if the directory cannot be opened.

Signature: `getFilenames(string $source_dir, bool $include_path = false, bool $_recursion = false): mixed`

---

## 10. Directory Report — `getDirFileInfo()`

Like `getFilenames()`, but each entry carries size, date and permissions info.

```php
$info = File::getDirFileInfo('/var/www/html/storage');

foreach ($info as $entry) {
    echo $entry['name'] . ' — ' . $entry['size'] . ' bytes' . PHP_EOL;
}
// Each entry: name, server_path, size, date, relative_path
```

Pass `false` as second argument to descend into subdirectories too (default
is top level only):

```php
$all = File::getDirFileInfo('/var/www/html/storage', false);
```

Signature: `getDirFileInfo(string $source_dir, bool $top_level_only = true, bool $_recursion = false): mixed`

---

## 11. Single File Report — `getFileInfo()`

Returns name, path, size and modification date by default; ask for more with
the second parameter (array or comma-separated string).

```php
$info = File::getFileInfo('/var/www/html/storage/report.pdf');
print_r($info);
// Array ( [name] => report.pdf [server_path] => ... [size] => 48211 [date] => 1789532251 )

// Custom fields
$info = File::getFileInfo($path, ['name', 'size', 'readable', 'writable']);
// or: File::getFileInfo($path, 'name,size,readable,writable');
```

Available keys: `name`, `server_path`, `size`, `date`, `readable`,
`writable`, `executable`, `fileperms`. Returns `false` if the file is missing.

Signature: `getFileInfo(string $file, mixed $returned_values = [...]): mixed`

---

## 12. MIME Type by Extension — `getMimeByExtension()`

Looks up `System/Config/Mimes.php`. Matching is case-insensitive; when an
extension maps to several types, the first one is returned.

```php
File::getMimeByExtension('photo.JPG');  // "image/jpeg"
File::getMimeByExtension('data.csv');   // "text/x-comma-separated-values"
File::getMimeByExtension('archive.unknownext'); // false
```

> Convenience only — an extension says nothing about actual file content.
> **Never use this for security decisions** (e.g. validating uploads); inspect
> the content instead (e.g. `finfo` / `mime_content_type()`).

Need the whole map? `File::getMimes()` returns the full extension → type
array from the config file.

Signatures:

```php
File::getMimeByExtension(string $filename): string|false
File::getMimes(): array
```

---

## 13. Complete Example — Simple Upload Handler Sketch

```php
use System\Libraries\File;

$uploadDir = '/var/www/html/storage/uploads';

// 1. Make sure the target directory exists
if (!is_dir($uploadDir)) {
    File::createDir($uploadDir);
}

// 2. Move the uploaded file, then verify it
move_uploaded_file($_FILES['doc']['tmp_name'], $uploadDir . '/doc.pdf');

if (!File::exists($uploadDir . '/doc.pdf')) {
    die('Upload failed.');
}

// 3. Inspect and report
$info = File::getFileInfo($uploadDir . '/doc.pdf', ['name', 'size', 'date']);
echo $info['name'] . ' (' . $info['size'] . ' bytes) stored successfully.';
```

---

## File Quick Reference

| Method | Purpose | Returns |
| --- | --- | --- |
| `File::exists($file)` | File or URL exists? | `bool` |
| `File::writeFile($path,$data,$mode)` | Write (locked) / create file | `bool` |
| `File::delete($file)` | Delete one file | `bool` |
| `File::createDir($path)` | Create dirs recursively | `bool` |
| `File::copyDirRecursively($from,$to)` | Copy whole tree | `bool` |
| `File::deleteEmptyDir($path)` | Remove empty dir | `bool` |
| `File::deleteDirRecursively($path)` | Remove dir with contents | `bool` |
| `File::getFilesFromDir($path)` | Iterate with `DirectoryIterator` | `object\|false` |
| `File::getFilenames($dir,$include_path)` | Flat recursive filename list | `array\|false` |
| `File::getDirFileInfo($dir,$top_only)` | Recursive file report | `array\|false` |
| `File::getFileInfo($file,$fields)` | Single file report | `array\|false` |
| `File::getMimeByExtension($f)` | Extension → MIME type | `string\|false` |
| `File::getMimes()` | Full MIME map | `array` |

Related source: `System/Libraries/File.php`, MIME config at `System/Config/Mimes.php`.

> **Note (Option 2):** Global helper shortcuts (`System/Core/NSY_Helpers_File.php` — `check_file()`, `create_file()`, …) have been removed. Use `System\Libraries\File` directly.

---

# NSY LanguageCode Library — User Tutorial

A practical guide to the language-code library at
`System/Libraries/LanguageCode.php` (namespace `System\Libraries`, class
`LanguageCode`). It maps between ISO 639-1 language codes (`'es'`, `'id'`,
`'en-gb'`, …) and language names (`'Spanish'`, `'Indonesian'`, …) using the
217-entry dataset in `System/Libraries/LanguageCodeCollection.php`. All
methods are **static**.

## 1. Getting Started with LanguageCode

```php
use System\Libraries\LanguageCode;

echo LanguageCode::getLanguageFromCode('es'); // "Spanish"
```

> PSR-4 autoloaded (`System\` → `System/`), usable anywhere after
> `System/Vendor/autoload.php` is loaded.

## 2. List Every Language — `get()`

Returns the full `code => name` map (217 entries).

```php
$languages = LanguageCode::get();

echo count($languages); // 217

foreach ($languages as $code => $name) {
    echo "$code — $name" . PHP_EOL;
}
// es — Spanish
// id — Indonesian
// en-gb — English (United Kingdom)
// ...
```

Signature: `get(): array`

## 3. Name from Code — `getLanguageFromCode()`

Lookup is **case-insensitive** and trims surrounding whitespace, so user
input can be passed directly. Regional variants (`'en-gb'`, `'ar-eg'`, …)
work as well. Returns `false` for unknown codes.

```php
LanguageCode::getLanguageFromCode('es');     // "Spanish"
LanguageCode::getLanguageFromCode('ES');     // "Spanish"
LanguageCode::getLanguageFromCode('en-GB');  // "English (United Kingdom)"
LanguageCode::getLanguageFromCode('  id  '); // "Indonesian"

$code = $_GET['lang'] ?? 'en';
$name = LanguageCode::getLanguageFromCode($code);

if ($name === false) {
    die('Unsupported language code.');
}
echo "Selected language: $name";
```

Signature: `getLanguageFromCode(string $languageCode): string|false`

## 4. Code from Name — `getCodeFromLanguage()`

Reverse lookup, also **case-insensitive**. Exact-case matches take a fast
path; anything else falls back to a case-insensitive scan. Returns `false`
for unknown names.

```php
LanguageCode::getCodeFromLanguage('Spanish'); // "es"
LanguageCode::getCodeFromLanguage('spanish'); // "es"
LanguageCode::getCodeFromLanguage('SPANISH'); // "es"
LanguageCode::getCodeFromLanguage('Klingon'); // false
```

Signature: `getCodeFromLanguage(string $languageName): string|false`

## 5. Application Helpers

`get_lang_code()` (dual-mode, defined in `System/Core/NSY_Helpers_Global.php`) wraps the library:

```php
get_lang_code();            // "id-ID" — the application's own locale
get_lang_code('Spanish');   // "es" — lookup by language name
get_lang_code('Klingon');   // false — unknown name
```

> `<html lang="@( get_lang_code() )">` in templates uses this helper. For all other language features use `LanguageCode::` directly.

> **Note:** Former shortcuts `get_all_lang()` / `get_lang_name()` from `NSY_Helpers_Language.php` have been removed (Option 2). Use `LanguageCode::get()` / `LanguageCode::getLanguageFromCode()` directly.

## 6. Complete Example — Language Dropdown

```php
use System\Libraries\LanguageCode;

$selected = $_GET['lang'] ?? 'en';

if (LanguageCode::getLanguageFromCode($selected) === false) {
    $selected = 'en'; // fall back to a safe default
}

echo '<select name="lang">';
foreach (LanguageCode::get() as $code => $name) {
    $isSelected = ($code === $selected) ? ' selected' : '';
    echo "<option value=\"$code\"$isSelected>$name</option>";
}
echo '</select>';
```

## LanguageCode Quick Reference

| Method / Helper | Purpose | Returns |
| --- | --- | --- |
| `LanguageCode::get()` | Full 217-entry code → name map | `array` |
| `LanguageCode::getLanguageFromCode($code)` | Name from code (case-insensitive) | `string\|false` |
| `LanguageCode::getCodeFromLanguage($name)` | Code from name (case-insensitive) | `string\|false` |
| `get_lang_code()` | Application locale | `string` |
| `get_lang_code($name)` | Lookup via `LanguageCode` | `string\|false` |

Related source: `System/Libraries/LanguageCode.php`, dataset in `System/Libraries/LanguageCodeCollection.php`.
`get_lang_code()` lives in `System/Core/NSY_Helpers_Global.php`.

---

# NSY LoadTime Library — User Tutorial

A practical guide to the stopwatch library at
`System/Libraries/LoadTime.php` (namespace `System\Libraries`, class
`LoadTime`). It measures how long a block of code takes — handy for footer
timers (`"Page generated in 0.0423 seconds"`) or quick profiling. All
methods are **static**.

## 1. Measuring Execution Time

Start the timer, run your code, then read the elapsed seconds:

```php
use System\Libraries\LoadTime;

LoadTime::start();

// ... code you want to measure ...
usleep(1500);

$seconds = LoadTime::end(); // e.g. 0.0016
echo "Done in $seconds seconds.";
```

Rules worth knowing:

```php
LoadTime::isActive(); // false — timer not running
LoadTime::end();      // false — nothing was started, safe to call

LoadTime::start();
LoadTime::isActive(); // true

LoadTime::end();      // float, e.g. 0.0234
LoadTime::isActive(); // false — end() resets the timer (one-shot)
```

> The timer is one-shot: every `end()` stops and resets it, so call
> `start()` again for the next measurement.

Signatures:

```php
LoadTime::start(): float
LoadTime::end(): float|false
LoadTime::isActive(): bool
```

## 2. Usage

```php
use System\Libraries\LoadTime;

LoadTime::start();
// ... measured code ...
echo 'Rendered in ' . LoadTime::end() . ' seconds.';
if (LoadTime::isActive()) { /* timer still running */ }
```

## LoadTime Quick Reference

| Method | Purpose | Returns |
| --- | --- | --- |
| `LoadTime::start()` | Start (or restart) the timer | `float` (microtime) |
| `LoadTime::end()` | Stop, reset, read elapsed seconds | `float\|false` |
| `LoadTime::isActive()` | Is the timer running? | `bool` |

Related source: `System/Libraries/LoadTime.php`.

> **Note:** Former helpers `loadtime_start()` / `loadtime_end()` / `loadtime_active()` (`NSY_Helpers_LoadTime.php`) removed — use `LoadTime::` directly.

---

# NSY Request Library — User Tutorial

A practical guide to the HTTP-input library at
`System/Libraries/Request.php` (namespace `System\Libraries`, class
`Request`). It answers "which method is this?", reads GET/POST/PUT/DELETE
input, and sanitizes values through the Validate library (Part E).

## 1. Which Method Is This?

```php
use System\Libraries\Request;

if (Request::isPost()) {
    // handle form submission
}

Request::isGet();    // bool
Request::isPut();    // bool
Request::isDelete(); // bool
```

Signatures: `Request::isGet(): bool` (same shape for `isPost/isPut/isDelete`)

> The checks read `$_SERVER['REQUEST_METHOD']` and safely return `false` when it is missing (e.g. CLI).

## 2. Reading Input — `input()`

Pick a source — `'GET'`, `'POST'`, `'PUT'` or `'DELETE'` (case-insensitive)
— and you get back a small reader function. Call it with a key to select a
value, then sanitize it with any `as*()` method:

```php
$get = Request::input('GET');

$name = $get('name')->asString('guest');  // "guest" when missing
$page = $get('page')->asInteger(1);       // 1 when missing/invalid
```

Things to know:

- The type name is case-insensitive: `Request::input('get')` works.
- Each `($key)` call returns an **independent** object, so holding two
  selections is safe: `$a = $get('x'); $b = $get('y');` — reading `$a`
  later still gives you `x`.
- Other method names (e.g. `'PATCH'`) yield an empty input set rather than
  crashing.
- `PUT`/`DELETE` bodies are parsed from `php://input` (JSON, form-encoded
  and multipart supported); an empty body simply yields an empty set.

Signature: `Request::input(string $type): callable`

## 3. Sanitizing to Array / Object / JSON

Read the whole input (or one key) and convert it in one step. The optional
`$filters` map declares the expected type per key; `$default` fills in
missing or invalid values:

```php
$post = Request::input('POST');

$user = $post()->asArray(
    ['name' => 'string', 'age' => 'integer', 'email' => 'email'],
    'n/a'
);
// ['name' => ..., 'age' => ..., 'email' => ...]

$obj  = $post()->asObject(['name' => 'string']);
$json = $post('items')->asJson(); // one key, encoded back to JSON
```

Valid filter names: `array`, `object`, `string`, `integer`, `float`,
`boolean`, `ip`, `url`, `email` (each maps to the matching `as*()` method
from Part E).

Signatures:

```php
$post()->asArray(array $filters = [], $default = null): array
$post()->asObject(array $filters = [], $default = null): \stdClass
$post()->asJson(mixed $default = null): mixed
```

## 4. Sanitizing Single Values

Each method reads the selected key and coerces it, falling back to
`$default` when the key is missing or invalid:

```php
$get = Request::input('GET');

$get('name')->asString('guest');   // tags stripped, quotes kept
$get('page')->asInteger(1);        // int or 1
$get('ratio')->asFloat(0.0);       // float or 0.0
$get('active')->asBoolean(false);  // bool or false
$get('ip')->asIp();                // valid IP or null
$get('site')->asUrl();             // sanitized URL or null
$get('mail')->asEmail();           // valid email or null
```

Signatures (same shape for every method):

```php
$get('key')->asString(mixed $default = null): mixed
// asInteger / asFloat / asBoolean / asIp / asUrl / asEmail likewise
```

## 5. Content Type

```php
Request::getContentType(); // e.g. "application/json"
```

Reads the `Content-Type` request header with any `; charset=…` suffix
removed. Returns `""` when the header is absent.

Signature: `Request::getContentType(): string`

## 6. One-Shot Readers (via Input)

For convenience, keep using `Request::input()` directly — no helper needed:

```php
$data = Request::input('POST')->asArray(['name' => 'string'], 'n/a');
$name = Request::input('GET')('name')->asString('guest');
```

## Request Quick Reference

| Method | Purpose | Returns |
| --- | --- | --- |
| `Request::isGet/isPost/isPut/isDelete()` | Current HTTP method? | `bool` |
| `Request::input($type)` | Reader for GET/POST/PUT/DELETE | `callable` |
| `$r()->asArray($filters,$default)` | Whole input as array | `array` |
| `$r()->asObject($filters,$default)` | Whole input as object | `stdClass` |
| `$r()->asJson($default)` | Input (or key) as JSON | `mixed` |
| `$r($key)->asString/asInteger/…` | Single sanitized value | `mixed` |
| `Request::getContentType()` | Content-Type without suffix | `string` |

Related source: `System/Libraries/Request.php`, sanitizers in `System/Libraries/Validate.php`.

> **Note:** Former helpers `request_is_*()` / `request_as_*()` / `request_content_type()` (`NSY_Helpers_Request.php`) removed — use `Request::` directly.

---

# NSY Validate Library — User Tutorial

A practical guide to the sanitizer library at
`System/Libraries/Validate.php` (namespace `System\Libraries`, class
`Validate`). Every method takes a value plus a `$default` that is returned
when the value is missing or invalid — so you always get something usable
back. All methods are **static**.

## 1. Sanitizing Whole Values

```php
use System\Libraries\Validate;

Validate::asArray(['a' => 1]);          // ['a' => 1]
Validate::asArray('not-array', 'dflt'); // "dflt"

Validate::asObject(['a' => 1]);  // stdClass { a: 1 }
Validate::asJson(['a' => 1]);    // '{"a":1}'
Validate::asJson('already');     // "already" (strings pass through)
```

Signatures:

```php
Validate::asArray(mixed $data, mixed $default = null): mixed
Validate::asObject(mixed $data, mixed $default = null): mixed
Validate::asJson(mixed $data, mixed $default = null): mixed
```

## 2. Strings, Integers, Floats, Booleans

```php
Validate::asString('<b>hi</b>');       // "hi" (tags stripped, quotes kept)
Validate::asString('<a href="x">it"s</a>'); // 'it"s'
Validate::asString(123);               // "123"
Validate::asString(null);              // null (the default)

Validate::asInteger('42');             // 42
Validate::asInteger('abc', 'n/a');     // "n/a"

Validate::asFloat('1.5');              // 1.5
Validate::asFloat('x', 0.0);           // 0.0

Validate::asBoolean('yes');            // true
Validate::asBoolean('no');             // false
Validate::asBoolean('maybe', 'n/a');   // "n/a"
```

> `asString()` accepts only scalar values — arrays and objects fall back to
> `$default`. Non-string scalars are cast first (`123` → `"123"`).

Signatures (same shape for every method):

```php
Validate::asString(mixed $data, mixed $default = null): mixed
// asInteger / asFloat / asBoolean likewise
```

## 3. IP, URL and Email

```php
Validate::asIp('1.2.3.4');              // "1.2.3.4"
Validate::asIp('nope', 'unknown');      // "unknown"

Validate::asUrl('https://example.id');  // sanitized URL
Validate::asUrl('', 'none');            // "none"

Validate::asEmail('me@example.id');     // "me@example.id"
Validate::asEmail('not-an-email');      // null (the default)
```

> `asUrl()` sanitizes rather than strictly validates — for security checks,
> validate the URL's host/scheme yourself after sanitizing.

Signatures:

```php
Validate::asIp(mixed $data, mixed $default = null): mixed
Validate::asUrl(mixed $data, mixed $default = null): mixed
Validate::asEmail(mixed $data, mixed $default = null): mixed
```

## Validate Quick Reference

| Method | Purpose | Returns |
| --- | --- | --- |
| `Validate::asArray($data,$default)` | Value as array | `mixed` |
| `Validate::asObject($data,$default)` | Value as object | `mixed` |
| `Validate::asJson($data,$default)` | Value as JSON | `mixed` |
| `Validate::asString($data,$default)` | Tags stripped, quotes kept | `mixed` |
| `Validate::asInteger($data,$default)` | Valid int or default | `mixed` |
| `Validate::asFloat($data,$default)` | Valid float or default | `mixed` |
| `Validate::asBoolean($data,$default)` | Bool or default | `mixed` |
| `Validate::asIp($data,$default)` | Valid IP or default | `mixed` |
| `Validate::asUrl($data,$default)` | Sanitized URL or default | `mixed` |
| `Validate::asEmail($data,$default)` | Valid email or default | `mixed` |

Related source: `System/Libraries/Validate.php`.

> **Note:** Former helpers `validate_*()` (`NSY_Helpers_Validate.php`) removed — use `Validate::` directly.
