# NSY Maintenance Mode — User Tutorial

Maintenance mode takes your site offline with a friendly **503** page while you
deploy, migrate or debug. Every request is short-circuited before routing, so no
controller runs and no database work happens while it is on.

- **Class:** `System\Core\NSY_Maintenance`
- **Flag file:** `System/Storage/maintenance.flag` (gitignored)
- **CLI:** `nsy down [message]` / `nsy up`
- **Config:** `APP_MAINTENANCE`, `APP_MAINTENANCE_ALLOW` in `env.php`

## Table of Contents

1. [Turning It On](#turning-it-on)
2. [Allow-listed IPs](#allow-listed-ips)
3. [The 503 Page](#the-503-page)
4. [How It Works](#how-it-works)
5. [Quick Reference](#quick-reference)

---

## Turning It On

Two ways — a runtime flag or a config value:

```sh
# Runtime flag (no config edit; delete the file to go back up)
nsy down                          # default message
nsy down "Upgrading the database" # custom message
nsy up                            # back online
```

```php
// env.php — permanent/CI-driven
'APP_MAINTENANCE' => 'true',
```

The flag file wins when present, so `nsy down` works even if the config is off.
The first line of the flag file becomes the message shown to visitors.

---

## Allow-listed IPs

Let yourself (or your team) browse the live site while everyone else sees the
maintenance page:

```php
// env.php — comma-separated client IPs
'APP_MAINTENANCE_ALLOW' => '203.0.113.10,198.51.100.7',
```

Allow-listed IPs skip the 503 entirely. This also works while `APP_MAINTENANCE`
is `true`.

---

## The 503 Page

The response is a real 503 with a `Retry-After: 3600` header:

- **Browsers** get `System/Apps/Templates/Errors/503.php` (plain PHP + the shared
  `_layout.php`).
- **API clients** (`Accept: application/json` or AJAX) get JSON:
  `{"error":{"code":503,"message":"…"}}`.

To change the look, edit the template; the `$message` variable holds the text.

---

## How It Works

`NSY_RouterOptimized::dispatch()` calls `NSY_Maintenance::check()` before any
route matching. The check is on when:

1. `System/Storage/maintenance.flag` exists, **or**
2. config `maintenance` (from `APP_MAINTENANCE`) is truthy,

and the request IP is not allow-listed. It then sends 503 and stops.

The same error-page mechanism serves `404.php` and `500.php` — see
[Router → Error pages](README_NSY_ROUTER.md#error-pages).

---

## Quick Reference

| API | Purpose | Returns |
|---|---|---|
| `NSY_Maintenance::active()` | Is maintenance on? | `bool` |
| `NSY_Maintenance::message()` | Visitor message | `string` |
| `NSY_Maintenance::allowed()` | Is the client allow-listed? | `bool` |
| `NSY_Maintenance::check()` | Enforce (sends 503 + exits) | `void` |
| `NSY_Maintenance::down($msg)` | Write the flag file (on) | `bool` |
| `NSY_Maintenance::up()` | Remove the flag file (off) | `bool` |
| `NSY_Maintenance::flagFile()` | Absolute flag path | `string` |

Related: `System/Core/NSY_Maintenance.php`, `System/Core/NSY_ErrorPage.php`,
`System/Apps/Templates/Errors/`, [docs/README_NSY_ROUTER.md](README_NSY_ROUTER.md).
