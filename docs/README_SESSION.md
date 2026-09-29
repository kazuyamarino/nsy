# NSY Session Library — User Tutorial

Native session helper at `System/Libraries/Session.php` (namespace
`System\Libraries`, class `Session`). Replaces the former
`josantonius/session` dependency. All methods are **static** and wrap PHP's
`session_*` functions.

## Starting a Session

NSY starts the session at boot (`NSY_System::initializeSession()`), using the
`session_config` array in `System/Config/App.php`.

```php
use System\Libraries\Session;

Session::start();                 // uses default options
Session::start(['cookie_samesite' => 'Strict']);
Session::isActive();              // bool
Session::id();                    // current session id
```

`start()` is idempotent and returns `false` (without error) when headers were
already sent.

## Reading & Writing

```php
Session::set('user_id', 42);
Session::get('user_id');              // 42
Session::get('missing', 'default');   // 'default'
Session::has('user_id');              // true
Session::remove('user_id');           // remove one
Session::remove('a', 'b');            // remove many
Session::all();                       // whole $_SESSION
```

## Lifecycle

```php
Session::regenerate();    // new id after login (keeps data, deletes old id)
Session::destroy();       // clear $_SESSION + session_destroy()
```

Both return `false` when no session is active or headers were sent.

## Flash Messages

Flash values survive exactly one read:

```php
Session::flash('notice', 'Saved successfully.');

// next request / later in the same flow:
echo Session::getFlash('notice');   // 'Saved successfully.'
echo Session::getFlash('notice');   // null (consumed)
```

## Quick Reference

| Method | Purpose | Returns |
| --- | --- | --- |
| `Session::start($options)` | Start session | `bool` |
| `Session::isActive()` | Session running? | `bool` |
| `Session::get($k,$default)` | Read value | `mixed` |
| `Session::set($k,$v)` | Write value | `void` |
| `Session::has($k)` | Key exists? | `bool` |
| `Session::remove(...$k)` | Remove keys | `void` |
| `Session::all()` | Whole session | `array` |
| `Session::id()` | Session id | `string` |
| `Session::regenerate($deleteOld)` | New id | `bool` |
| `Session::destroy()` | Destroy session | `bool` |
| `Session::flash($k,$v)` / `Session::getFlash($k)` | One-shot value | `void` / `mixed` |

Related source: `System/Libraries/Session.php`. Tests: `System/Test/Libraries/SessionTest.php`.
