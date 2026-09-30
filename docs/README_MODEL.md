# NSY Model & DB — User Tutorial

Query builder for NSY (`System/Core/DB.php`, `System/Core/NSY_DB.php`). Unified via `NSY_DB::connect()` (single source, `declare(strict_types=1)`, `PDO::ERRMODE_EXCEPTION`), `declare(strict_types=1)` in both.

- **DB:** `System\Core\DB` — DML/query (SELECT/INSERT/UPDATE/DELETE) + `fetch*`, `exec`, `multiInsert`
- **NSY_DB:** `System\Core\NSY_DB` — low-level factory (mysql/dblib/pgsql/sqlsrv) used by `DB` and `NSY_Migration`
- **Config:** `env.php` `connections` (4: `primary/mysql/pgsql/sqlsrv`) + `System/Config/App.php` (`transaction`, `csrf_token`)

## Table of Contents

1. [Connections](#connections)
2. [Basic Query](#basic-query)
3. [Binding](#binding)
4. [Fetching](#fetching)
5. [Execute & Multi Insert](#execute--multi-insert)
6. [Transactions](#transactions)
7. [Migrated Connection (NEW)](#migrated-connection-new)
8. [Quick Reference](#quick-reference)

---

## Connections

Connections are declared once in `env.php` under `connections` and selected by name.

```php
// env.php
'connections' => [
  'primary'   => ['DB_CONNECTION' => 'mysql', 'DB_HOST' => 'localhost', 'DB_NAME' => 'nsy', ...],
  'secondary' => ['DB_CONNECTION' => 'pgsql', ...],
  'mysql'     => [...], // blank template
  'pgsql'     => [...],
  'sqlsrv'    => [...],
]

DB::connect()->query($q)->fetchAll();              // primary
DB::connect('secondary')->query($q)->fetchAll();   // secondary
DB::connect('pgsql')->query($q)->fetchAll();       // custom
```

> `DB::connect(string $conn='primary'): object` `System/Core/DB.php:33` delegates to `NSY_DB::connect($conn)` `System/Core/NSY_DB.php:24` — one `match` selects the driver (no duplicated `switch`).

### Environment keys

| Key | Required | Purpose |
|---|---|---|
| `DB_CONNECTION` | yes | Driver: `mysql`, `pgsql`, `dblib` or `sqlsrv` |
| `DB_HOST` | yes | Server host |
| `DB_NAME` | yes | Database name |
| `DB_PORT` | — | Port (appended when filled) |
| `DB_USER` / `DB_PASS` | — | Credentials (default `''`) |
| `DB_CHARSET` | — | Charset (mysql / pgsql / dblib) |
| `DB_ATTR` | — | PDO options array, merged over the secure defaults |

Missing `DB_CONNECTION`, `DB_HOST` or `DB_NAME` raises a configuration error through
`NSY_Desk::staticErrorHandler()`.

### Supported drivers

| `DB_CONNECTION` | PDO extension | DSN built by | Format |
|---|---|---|---|
| `mysql` | `pdo_mysql` | `buildDsn()` | `mysql:host=…;port=…;dbname=…;charset=…` |
| `pgsql` | `pdo_pgsql` | `buildDsn()` | `pgsql:host=…;port=…;dbname=…;charset=…` |
| `dblib` | `pdo_dblib` | `buildDsn()` | `dblib:host=…;port=…;dbname=…;charset=…` |
| `sqlsrv` | `pdo_sqlsrv` | `buildSqlsrvDsn()` | `sqlsrv:Server=host,port;Database=…` |

Any other value falls through to `handleUnknownDriver()` and reports an error.

### Connection options

`normalizeOptions()` applies secure defaults, which your `DB_ATTR` overrides:

```php
\PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
\PDO::ATTR_EMULATE_PREPARES   => false,
```

Every attempt is logged on the `db` channel (driver / host / port / dbname + duration) —
**credentials are never logged**.

### Backwards-compatible proxies

`NSY_DB` also exposes the per-driver entry points `connectMysql()`, `connectDblib()` and
`connectPgsql()` (generic DSN) plus `connectSqlsrv()` (SQLSRV DSN). They are kept for
backwards compatibility; for new code prefer `connect($conn)`.

---

## Basic Query

```php
$q = 'SELECT * FROM users WHERE id = :id';
DB::connect()->query($q)->vars([':id' => 2])->fetch();
// Same: DB::connect('primary')->query($q)->vars([...])->fetchAll();
```

`query(string $q)` validates via `is_filled()` → `NSY_Desk::staticErrorHandler()`.

---

## Binding

```php
// Simple bind (auto)
$q = "SELECT * FROM users WHERE id = :id";
DB::connect()->query($q)->vars([':id' => 2])->fetch();

// Typed BINDVALUE/BINDPARAM
$vars = [':id' => [2, PAR_INT], ':name' => ['%a%', PAR_STR]];
DB::connect()->query($q)->vars($vars)->bind(BINDVAL)->fetch();
DB::connect()->query($q)->vars($vars)->bind(BINDPAR)->fetch();
```

---

## Fetching

```php
DB::connect()->query($q)->vars()->style(FETCH_ASSOC)->fetchAll(); // all rows
DB::connect()->query($q)->fetch();              // single row
DB::connect()->query($q)->fetchColumn(0);      // single value
DB::connect()->query($q)->rowCount();          // affected rows

// FETCH_* constants: FETCH_NUM, FETCH_ASSOC, FETCH_BOTH, FETCH_COLUMN, FETCH_OBJ, FETCH_KEY_PAIR...
```

---

## Execute & Multi Insert

```php
// Update/delete/insert
$q = "UPDATE users SET name = :name WHERE id = :id";
DB::connect()->query($q)->vars($p)->bind()->exec(); // true, with transaction + CSRF check

// Multi insert
$q = "INSERT INTO users (id, name, user_name)";
$arr = [[1,'A','a'], [2,'B','b']];
DB::connect()->query($q)->vars($arr)->multiInsert();
```

`exec()` checks `config_app('csrf_token')` via `SecurityMiddleware::validateAdvancedCSRF` + `transaction` (`on` → `beginTransaction/commit/rollback`).

---

## Transactions

```php
// Auto via config
// System/Config/App.php:92 'transaction' => config_env('DB_TRANSACTION') ?? 'off'
DB::connect()->query($q)->vars($p)->exec(); // handles begin/commit/rollback internally

// Manual
DB::connect()->beginTrans();
DB::connect()->query($q)->vars($p)->exec();
DB::connect()->commitTrans();
DB::connect()->rollbackTrans();

// PDO attributes
DB::connect()->pdoSetAttr(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
DB::connect()->pdoGetAttr(PDO::ATTR_ERRMODE);
```

---

## Migrated Connection (NEW)

Before (duplicated):
```php
switch(config_db(...)) { case 'mysql': NSY_DB::connectMysql(...) }
```
After (unified):
```php
// System/Core/DB.php:33 & System/Core/NSY_Migration.php:27
NSY_DB::connect(string $conn): ?PDO // match driver → createConnection/buildDsn/normalizeOptions
```
Benefits: `declare(strict_types=1)`, `?PDO` type, `buildDsn()` + `quoteIdent()` safety, `normalizeOptions()` defaults (`ERRMODE_EXCEPTION`, `FETCH_ASSOC`).

---

## Quick Reference

| Method | Purpose | Returns |
|---|---|---|
| `DB::connect($conn)` | Select connection | `object` |
| `NSY_DB::connect($conn)` | Driver factory (`mysql`/`pgsql`/`dblib`/`sqlsrv`) | `?PDO` |
| `query($q)` / `vars($arr)` / `bind(BINDVAL)` / `style(FETCH_ASSOC)` | Build query | `object` (chainable) |
| `fetchAll()` / `fetch()` / `fetchColumn($i)` / `rowCount()` | Fetch | `array/mixed/int` |
| `exec()` / `multiInsert()` | Execute DML | `bool` |
| `beginTrans()` / `commitTrans()` / `rollbackTrans()` | Transaction | `object` |
| `pdoSetAttr($k,$v)` | PDO attribute | `object` |

Related: `System/Core/DB.php:33`, `System/Core/NSY_DB.php:24`, `env.php` `connections`, `System/Core/NSY_Migration.php` (DDL counterpart).
