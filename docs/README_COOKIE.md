# NSY Cookie Library — User Tutorial

Native cookie helper at `System/Libraries/Cookie.php` (namespace
`System\Libraries`, class `Cookie`). Replaces the former `josantonius/cookie`
dependency. All methods are **static** and secure-by-default
(`HttpOnly`, `SameSite=Lax`, `Path=/`).

## Quick Start

```php
use System\Libraries\Cookie;

Cookie::set('theme', 'dark', time() + 3600);
echo Cookie::get('theme', 'light');
```

## Setting a Cookie

```php
// set(name, value, expires = 0, options = [])
Cookie::set('token', $value, time() + 7200);

// or pass options in place of expires
Cookie::set('token', $value, [
    'expires'  => time() + 7200,
    'secure'   => true,
    'samesite' => 'Strict',
]);
```

`set()` returns `false` when headers were already sent (nothing to send).

## Reading

```php
Cookie::get('theme');            // value or null
Cookie::get('theme', 'light');   // value or default
Cookie::has('theme');            // bool
Cookie::all();                   // ['theme' => 'dark', ...]
```

> Writes do not mutate `$_COOKIE` (only the browser does that); reads within the
> same request reflect the incoming request.

## Deleting

```php
Cookie::delete('token');         // expires the cookie and unsets $_COOKIE['token']
```

## Defaults

```php
Cookie::configure(['samesite' => 'Strict', 'secure' => true]);
Cookie::defaults();              // current defaults
```

| Option | Default | Notes |
| --- | --- | --- |
| `expires` | `0` | session cookie |
| `path` | `/` | |
| `domain` | `''` | current host |
| `secure` | `false` | set `true` under HTTPS |
| `httponly` | `true` | not readable from JS |
| `samesite` | `Lax` | `Lax` / `Strict` / `None` |

## Quick Reference

| Method | Purpose | Returns |
| --- | --- | --- |
| `Cookie::set($n,$v,$exp,$opts)` | Send a cookie | `bool` |
| `Cookie::get($n,$default)` | Read a cookie | `mixed` |
| `Cookie::has($n)` | Exists in request? | `bool` |
| `Cookie::all()` | All request cookies | `array` |
| `Cookie::delete($n,$opts)` | Expire + unset | `bool` |
| `Cookie::configure($opts)` / `Cookie::defaults()` | Defaults | `void` / `array` |

Related source: `System/Libraries/Cookie.php`. Tests: `System/Test/Libraries/CookieTest.php`.
