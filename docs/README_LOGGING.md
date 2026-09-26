# NSY Logging — User Guide

NSY ships a small, dependency-light file logger built on **strict PSR-3**.
It records PHP/Razr syntax and runtime errors, database connect/query failures,
HTTP access and migration activity — as **JSONL** by default, with rotation and
pruning handled for you.

The subsystem is **disabled by default** (`LOG_ENABLED=false`); enabling it is
one env key. When disabled, every log call is inert and costs essentially
nothing.

---

## 1. Quick start

In `env.php`:

```php
'LOG_ENABLED' => 'true',
'LOG_LEVEL'   => '',          // empty → debug in development, warning in production
```

Logs are written to `System/Storage/logs/nsy-YYYY-MM-DD.log`.

```bash
tail -f System/Storage/logs/nsy-$(date +%F).log
cat    System/Storage/logs/nsy-$(date +%F).log | jq .
```

---

## 2. Channels

Each event carries a `channel`; route/safety policy is per channel.

| Channel     | Purpose                                                        |
| ----------- | -------------------------------------------------------------- |
| `app`       | General application messages (PSR-3 usage from your code).      |
| `error`     | PHP diagnostics, funnel errors, fatal/uncaught exceptions.      |
| `db`        | Connection success/failure and slow queries.                    |
| `access`    | One line per HTTP request (method, uri, status, duration).      |
| `view`      | Razr/view render and syntax failures.                           |
| `security`  | Reserved for security middleware events.                        |
| `migration` | Migration execution and blocked web migrations.                 |

---

## 3. Usage (PSR-3)

```php
use System\Libraries\Log\LogManager;

LogManager::channel('app')->info('Order created', ['order_id' => 42]);
LogManager::channel('security')->warning('Login failed', ['user' => 'bob']);
LogManager::logger()->error('Something broke');
```

The returned object implements `Psr\Log\LoggerInterface` in full, so it can be
injected into any third-party package that type-hints a PSR-3 logger:

```php
$package->setLogger(LogManager::channel('app'));
```

All eight PSR-3 methods are available. NSY stores five levels
(`debug`, `info`, `warning`, `error`, `critical`) and normalises the rest:
`emergency`/`alert` → `critical`, `notice` → `info`.

### Slow queries

`DB::exec()` / `DB::multi_insert()` are timed automatically. Configure:

```php
'LOG_SLOW_QUERY_MS' => '500',
```

Queries above the threshold are logged to `db` with the SQL **normalised**
(string/number literals replaced by `?`):

```json
{"ts":"...","level":"warning","channel":"db","msg":"Slow query","duration_ms":812.4,"context":{"sql":"SELECT * FROM users WHERE id = ?"}}
```

---

## 4. Configuration reference (`env.php`)

| Key                   | Default                        | Meaning                                                        |
| --------------------- | ------------------------------ | -------------------------------------------------------------- |
| `LOG_ENABLED`         | `false`                        | Master switch.                                                 |
| `LOG_DIR`             | `System/Storage/logs`          | Directory; relative to project root or absolute.               |
| `LOG_LEVEL`           | `debug` (dev) / `warning` (prod) | Minimum stored level.                                        |
| `LOG_FORMAT`          | `json`                         | `json` (JSONL) or `text`.                                      |
| `LOG_SPLIT_CHANNELS`  | `false`                        | `true` → one sub-directory per channel.                        |
| `LOG_MAX_SIZE_MB`     | `50`                           | Rotate the daily file when it reaches this size.               |
| `LOG_RETENTION_DAYS`  | `14`                           | Files older than this are pruned.                              |
| `LOG_SLOW_QUERY_MS`   | `500`                          | Slow-query threshold.                                          |
| `ACCESS_LOG_ENABLED`  | `true`                         | Write one `access` line per request.                           |
| `LOG_IP`              | `false`                        | Include client IP (privacy: off by default).                   |
| `LOG_USER_AGENT`      | `false`                        | Include User-Agent.                                            |
| `LOG_USER_ID`         | `false`                        | Include `$_SESSION['user_id']` when present.                   |
| `LOG_REDACT`          | `password,passwd,secret,token,authorization,cookie,csrf` | Keys masked as `***` in context. |

The active values live in `System/Config/App.php` under the `log` key;
`env.php` (and the two examples) only carry the flat `LOG_*` entries.

---

## 5. File layout, rotation & retention

```
System/Storage/logs/
├── .htaccess                    # deny web access (tracked)
├── nsy-2026-09-26.log           # today
├── nsy-2026-09-26.1.log         # rotated after LOG_MAX_SIZE_MB
└── nsy-2026-09-25.log           # pruned after LOG_RETENTION_DAYS
```

With `LOG_SPLIT_CHANNELS=true`, files move to
`System/Storage/logs/<channel>/nsy-YYYY-MM-DD.log`.

Rotation and pruning happen inline on write; for heavier installations let the
system `logrotate` own the directory instead.

### Formats

JSONL (default) — one JSON object per line, ideal for `jq`/ELK/Datadog:

```json
{"ts":"2026-09-26T01:56:23+00:00","env":"development","level":"info","channel":"access","msg":"request","request_id":"fe50eeec40df0e6b","method":"GET","uri":"/nsy/","status":200,"duration_ms":17.49}
```

Text (`LOG_FORMAT=text`) — for quick `tail -f`:

```
2026-09-26T01:56:23+00:00 INFO [access] request  method=GET uri=/nsy/ status=200 duration_ms=17.49
```

Every record within a request shares one `request_id`, so access ↔ error ↔ db
lines can be correlated.

---

## 6. Security & deployment

Logs must never be reachable over HTTP. NSY writes them under `System/`, which
is **outside** the document root only when the web root points at `public/`.

Configurations shipped with the repo already deny the path:

- **Apache** — `System/.htaccess` and `System/Storage/logs/.htaccess`
  (`Require all denied`); the `public/` config also denies `*.log` files.
- **Nginx** — `docs/nginx/sites-enabled/default` adds
  `location ^~ /nsy/System/ { deny all; return 404; }` **before** the `/nsy/`
  block.
- **Dev server** (`nsy serve`) — `.cli/tmp/router.php` returns 404 for any
  `/System/` path.

When logging initialises, it compares `LOG_DIR` against `DOCUMENT_ROOT` and
emits one `warning` if the directory is web-reachable, so a misconfigured
deploy is visible immediately.

Recommended production hardening: keep `LOG_IP`/`LOG_USER_AGENT` off unless an
audit requires them; point `LOG_DIR` at a non-web path (e.g. `/var/log/nsy`) if
the host allows it.

---

## 7. Testing & verification

```bash
# every page should return 200/404 as expected
curl -s -o /dev/null -w '%{http_code}\n' http://host/nsy/
# logs must not be served
curl -s -o /dev/null -w '%{http_code}\n' http://host/nsy/System/Storage/logs/nsy-$(date +%F).log   # expect 404
# JSONL is well-formed
cat System/Storage/logs/nsy-$(date +%F).log | jq -e .
# no credentials leaked
rg -i 'password|passwd|secret|token|authorization' System/Storage/logs/ || echo clean
```

---

## 8. Notes

- Logging never throws: every write path is wrapped so a broken disk or bad
  permission cannot break a request.
- `DB::exec()` query failures previously used `die($message)` (leaking SQL
  detail); they now go through `NSY_Desk::static_error_handler()`, so the client
  sees a generic page in production while the detail is logged.
- PHP fatal/parse errors and uncaught exceptions are captured by a shutdown
  handler; dev screen output is unchanged.
