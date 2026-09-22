# NSY Model & DB — User Tutorial

Query builder for NSY (`System/Core/DB.php`, `System/Core/NSY_DB.php`). Unified via `NSY_DB::connect()` (single source, `declare(strict_types=1)`, `PDO::ERRMODE_EXCEPTION`), `declare(strict_types=1)` in both.

- **DB:** `System\Core\DB` — DML/query (SELECT/INSERT/UPDATE/DELETE) + `fetch_*`, `exec`, `multi_insert`
- **NSY_DB:** `System\Core\NSY_DB` — low-level factory (mysql/dblib/pgsql/sqlsrv) used by `DB` and `NSY_Migration`
- **Config:** `env.php` `connections` (4: `primary/mysql/pgsql/sqlsrv`) + `System/Config/App.php` (`transaction`, `csrf_token`)

## Table of Contents

1. [Connections](#1-connections)
2. [Basic Query](#2-basic-query)
3. [Binding](#3-binding)
4. [Fetching](#4-fetching)
5. [Execute & Multi Insert](#5-execute--multi-insert)
6. [Transactions](#6-transactions)
7. [Migrated Connection (NEW)](#7-migrated-connection-new)
8. [Quick Reference](#quick-reference)

---

## 1. Connections

```php
// env.php
'connections' => [
  'primary'   => ['DB_CONNECTION' => 'mysql', 'DB_HOST' => 'localhost', 'DB_NAME' => 'nsy', ...],
  'secondary' => ['DB_CONNECTION' => 'pgsql', ...],
  'mysql'     => [...], // blank template
  'pgsql'     => [...],
  'sqlsrv'    => [...],
]

DB::connect()->query($q)->fetch_all();              // primary
DB::connect('secondary')->query($q)->fetch_all();   // secondary
DB::connect('pgsql')->query($q)->fetch_all();       // custom
```

> `DB::connect(string $conn='primary'): object` `System/Core/DB.php:33` now delegates to `NSY_DB::connect($conn)` `System/Core/NSY_DB.php:15` — no duplicated `switch` (before 16 lines x2).

---

## 2. Basic Query

```php
$q = 'SELECT * FROM users WHERE id = :id';
DB::connect()->query($q)->vars([':id' => 2])->fetch();
// Same: DB::connect('primary')->query($q)->vars([...])->fetch_all();
```

`query(string $q)` validates via `is_filled()` → `NSY_Desk::static_error_handler()`.

---

## 3. Binding

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

## 4. Fetching

```php
DB::connect()->query($q)->vars()->style(FETCH_ASSOC)->fetch_all(); // all rows
DB::connect()->query($q)->fetch();              // single row
DB::connect()->query($q)->fetch_column(0);      // single value
DB::connect()->query($q)->row_count();          // affected rows

// FETCH_* constants: FETCH_NUM, FETCH_ASSOC, FETCH_BOTH, FETCH_COLUMN, FETCH_OBJ, FETCH_KEY_PAIR...
```

---

## 5. Execute & Multi Insert

```php
// Update/delete/insert
$q = "UPDATE users SET name = :name WHERE id = :id";
DB::connect()->query($q)->vars($p)->bind()->exec(); // true, with transaction + CSRF check

// Multi insert
$q = "INSERT INTO users (id, name, user_name)";
$arr = [[1,'A','a'], [2,'B','b']];
DB::connect()->query($q)->vars($arr)->multi_insert();
```

`exec()` checks `config_app('csrf_token')` via `SecurityMiddleware::validateAdvancedCSRF` + `transaction` (`on` → `beginTransaction/commit/rollback`).

---

## 6. Transactions

```php
// Auto via config
// System/Config/App.php:92 'transaction' => config_env('DB_TRANSACTION') ?? 'off'
DB::connect()->query($q)->vars($p)->exec(); // handles begin/commit/rollback internally

// Manual
DB::connect()->begin_trans();
DB::connect()->query($q)->vars($p)->exec();
DB::connect()->commit_trans();
DB::connect()->rollback_trans();

// PDO attributes
DB::connect()->pdo_set_attr(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
DB::connect()->pdo_get_attr(PDO::ATTR_ERRMODE);
```

---

## 7. Migrated Connection (NEW)

Before (duplicated):
```php
switch(config_db(...)) { case 'mysql': NSY_DB::connect_mysql(...) }
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
| `query($q)` / `vars($arr)` / `bind(BINDVAL)` / `style(FETCH_ASSOC)` | Build query | `object` (chainable) |
| `fetch_all()` / `fetch()` / `fetch_column($i)` / `row_count()` | Fetch | `array/mixed/int` |
| `exec()` / `multi_insert()` | Execute DML | `bool` |
| `begin_trans()` / `commit_trans()` / `rollback_trans()` | Transaction | `object` |
| `pdo_set_attr($k,$v)` | PDO attribute | `object` |

Related: `System/Core/DB.php:33`, `System/Core/NSY_DB.php:15`, `env.php` `connections`, `System/Core/NSY_Migration.php` (DDL counterpart).
