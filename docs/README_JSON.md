# NSY Json Library — User Tutorial

Native JSON helper at `System/Libraries/Json.php` (namespace `System\Libraries`,
class `Json`). All methods are **static**.

## Table of Contents

1. [Quick Start](#quick-start)
2. [Reading & Writing Files](#reading--writing-files)
3. [Encode & Decode](#encode--decode)
4. [Quick Reference](#quick-reference)

## Quick Start

```php
use System\Libraries\Json;

// Read
$config = Json::read('storage/app.json');          // array
$object = Json::read('storage/app.json', false);   // stdClass

// Write (atomic)
Json::write('storage/app.json', ['theme' => 'dark']);
```

## Reading & Writing Files

```php
Json::has('storage/app.json');                     // bool
$data = Json::read('storage/app.json');            // mixed (throws if malformed)
Json::write('storage/app.json', ['a' => 1]);       // bool — atomic
```

`write()` encodes to a sibling `*.tmp` file, then `rename()`s it into place, so a
crash never leaves a partially written file. Missing parent directories are
created.

## Encode & Decode

```php
$json = Json::encode(['a' => 1]);                  // pretty + unicode
$json = Json::encode(['a' => 1], 0);               // '{"a":1}'
$data = Json::decode('{"a":1}');                   // ['a' => 1]
$obj  = Json::decode('{"a":1}', false);            // stdClass
```

`encode()` and `decode()` throw `InvalidArgumentException` on failure instead of
returning `false`.

## Quick Reference

| Method | Purpose | Returns |
| --- | --- | --- |
| `Json::read($path, $assoc=true)` | Read + decode a file | `mixed` |
| `Json::write($path, $data, $flags)` | Encode + atomic write | `bool` |
| `Json::decode($json, $assoc=true)` | Decode a string | `mixed` |
| `Json::encode($data, $flags)` | Encode a value | `string` |
| `Json::has($path)` | File exists? | `bool` |

Related source: `System/Libraries/Json.php`. Tests: `System/Test/Libraries/JsonTest.php`.
