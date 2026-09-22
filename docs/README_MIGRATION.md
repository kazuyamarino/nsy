# NSY Migration — User Tutorial

Database versioning for NSY (`System/Core/NSY_Migration.php`, alias `Mig`). Powerful after refactor: unified `NSY_DB::connect()`, DRY `execDDL()` + `quoteIdent()` (backtick per identifier), chainable `return $this`, `declare(strict_types=1)`.

- **Migration class:** `System\Migrations\*` (created via CLI)
- **Engine:** `System\Core\NSY_Migration` (`Mig`) — DDL only (vs `System\Core\DB` for DML)
- **Route guard:** `System/Core/NSY_Migration_Route.php` — HTTP `/migup=(:any)` only when `APP_ENV === 'development'` (`System/Core/NSY_Desk.php:139` blocks production with 403)

## Table of Contents

1. [Creating a Migration](#1-creating-a-migration)
2. [Running Migrations](#2-running-migrations)
3. [Connection](#3-connection)
4. [Database Helpers](#4-database-helpers)
5. [Table Helpers](#5-table-helpers)
6. [Column Helpers](#6-column-helpers)
7. [Indexes](#7-indexes)
8. [Datatypes & Modifiers](#8-datatypes--modifiers)
9. [Security Notes](#9-security-notes)
10. [Quick Reference](#quick-reference)

---

## 1. Creating a Migration

```bash
nsy make:migrate create_supplier_table
# → System/Migrations/create_supplier_table.php
```

```php
<?php
use System\Core\NSY_Migration as Mig;

class create_supplier_table {
    public function up() {
        Mig::connect()->create_table('suppliers', [
            Mig::bigint('id', 20)->auto_increment(),
            Mig::varchar('name')->not_null(),
            Mig::primary('id')
        ])->index('BTREE', 'name');
    }
    public function down() {
        Mig::connect()->drop_exist_table(['suppliers']);
    }
}
```

---

## 2. Running Migrations

**CLI (recommended, production-safe):**
```bash
nsy show:migrate          # list
nsy run:migrate all       # all pending
nsy run:migrate list      # pick one
```

**HTTP (development only):**
```
GET /migup=create_supplier_table   # Mig::connect()->create_table...
GET /migdown=create_supplier_table # down()
```
> In `production` `System/Core/NSY_Desk.php:139` returns `403 Migrations are disabled`. Use CLI.

---

## 3. Connection

```php
Mig::connect()                 // primary (env.php connections.primary)
Mig::connect('secondary')      // secondary / custom (env.php connections.secondary)
Mig::connect('pgsql')          // pgsql / sqlsrv / dblib — via NSY_DB::connect() unified
```
> No `switch` duplication — `Mig::connect()` delegates to `NSY_DB::connect()` `System/Core/NSY_DB.php:15` (single source, `PDO::ERRMODE_EXCEPTION`).

---

## 4. Database Helpers

```php
Mig::connect()->create_database(['db1', 'db2']); // CREATE DATABASE `db1`; — quoted via quoteIdent()
Mig::connect()->drop_database(['db1']);
Mig::connect()->drop_exist_table(['t1']); // IF EXISTS
Mig::connect()->drop_table(['t1']);
```

---

## 5. Table Helpers

```php
Mig::connect()->create_table('users', [
    Mig::bigint('id')->auto_increment(),
    Mig::varchar('name')->not_null(),
    Mig::primary('id')
]);

Mig::connect()->rename_table('users', 'members');       // mysql: RENAME TABLE
Mig::connect()->rename_table_pg('users', 'members');    // pgsql: ALTER TABLE ... RENAME TO
Mig::connect()->rename_table_ms('users', 'members');    // sqlsrv: sp_rename
```

---

## 6. Column Helpers

```php
Mig::connect()->add_cols('users', [Mig::varchar('phone')->null()]);
Mig::connect()->add_cols_ms('users', [Mig::varchar('phone')->null()]); // sqlsrv: ADD
Mig::connect()->drop_cols('users', ['phone']);
Mig::connect()->modify_cols('users', [Mig::varchar('phone')->not_null()]);
Mig::connect()->modify_cols_ext('users', ['phone' => Mig::varchar('phone')->not_null()]);
Mig::connect()->rename_cols('users', ['phone' => 'mobile']);
Mig::connect()->change_cols('users', ['phone' => Mig::varchar('mobile')->not_null()]);
```

---

## 7. Indexes

```php
Mig::connect()->create_table('t', [..., Mig::primary('id')])->index('BTREE', 'name');
Mig::connect()->create_table('t', [...])->index('BTREE', ['a','b']);
Mig::connect()->create_table('t', [...])->index_pg('BTREE', 'name'); // pgsql: USING BTREE
```

Generated: `CREATE INDEX MULTI_1_5_IDX USING BTREE ON `t` ( name )`

---

## 8. Datatypes & Modifiers

```php
Mig::bigint('id',20)->auto_increment()
Mig::varchar('name',255)->not_null()->default('x')
Mig::text('bio')->null()
Mig::int('age')->default(0)
Mig::boolean('active')->not_null()
Mig::primary('id') / Mig::unique(['a','b'])
Mig::timestamps() // create_date/update_date/delete_date
```

Chain: `Mig::varchar('name')->not_null()->default('x')` → `name VARCHAR(255) NOT NULL DEFAULT x`

---

## 9. Security Notes

*   Identifiers quoted via `quoteIdent()` `System/Core/NSY_Migration.php:54` → `` `table` `` per part (`db.table` safe), `sp_rename` escaped `''`.
*   `execDDL()` `System/Core/NSY_Migration.php:64` centralizes `prepare/execute`, `rollback` on `transaction==='on'`.
*   `exit()` removed — methods `return $this` chainable, no dead `stmt=null` after return.
*   HTTP migration disabled in production — use CLI.

---

## Quick Reference

| Method | Purpose | Returns |
|---|---|---|
| `Mig::connect($conn)` | Select connection (primary/secondary/...) | `object` |
| `create_database([db])` / `drop_database([db])` | CREATE/DROP DATABASE | `object` |
| `create_table($t, [cols])` / `drop_table([t])` | CREATE/DROP TABLE | `object` |
| `rename_table($old,$new)` (+ `_pg`, `_ms`) | RENAME TABLE | `object` |
| `add_cols($t,[cols])` / `drop_cols` / `modify_cols` | ALTER TABLE cols | `object` |
| `index($type,$cols)` / `index_pg` | CREATE INDEX | `bool` |
| `primary($cols)` / `unique($cols)` | CONSTRAINT | `string` |
| `timestamps()` | 3 DATETIME cols | `array` |

Related: `System/Core/NSY_Migration.php:37`, `System/Core/NSY_DB.php:15`, `System/Core/NSY_Migration_Route.php:8`.
