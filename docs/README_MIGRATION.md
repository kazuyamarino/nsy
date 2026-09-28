# NSY Migration — User Tutorial

Database versioning for NSY (`System/Core/NSY_Migration.php`, alias `Mig`). Powerful after refactor: unified `NSY_DB::connect()`, DRY `execDDL()` + `quoteIdent()` (backtick per identifier), chainable `return $this`, `declare(strict_types=1)`.

- **Migration class:** `System\Migrations\*` (created via CLI)
- **Engine:** `System\Core\NSY_Migration` (`Mig`) — DDL only (vs `System\Core\DB` for DML)
- **Route guard:** `System/Core/NSY_Migration_Route.php` — HTTP `/migup=(:any)` only when `APP_ENV === 'development'` (`System/Core/NSY_Desk.php:139` blocks production with 403)

## Table of Contents

1. [Creating a Migration](#creating-a-migration)
2. [Running Migrations](#running-migrations)
3. [Connection](#connection)
4. [Database Helpers](#database-helpers)
5. [Table Helpers](#table-helpers)
6. [Column Helpers](#column-helpers)
7. [Indexes](#indexes)
8. [Datatypes & Modifiers](#datatypes--modifiers)
9. [Security Notes](#security-notes)
10. [Quick Reference](#quick-reference)

---

## Creating a Migration

```bash
nsy make:migrate create_supplier_table
# → System/Migrations/create_supplier_table.php
```

```php
<?php
use System\Core\NSY_Migration as Mig;

class create_supplier_table {
    public function up() {
        Mig::connect()->createTable('suppliers', [
            Mig::bigint('id', 20)->autoIncrement(),
            Mig::varchar('name')->notNull(),
            Mig::primary('id')
        ])->index('BTREE', 'name');
    }
    public function down() {
        Mig::connect()->dropExistTable(['suppliers']);
    }
}
```

---

## Running Migrations

**CLI (recommended, production-safe):**
```bash
nsy show:migrate          # list
nsy run:migrate all       # all pending
nsy run:migrate list      # pick one
```

**HTTP (development only):**
```
GET /migup=create_supplier_table   # Mig::connect()->createTable...
GET /migdown=create_supplier_table # down()
```
> In `production` `System/Core/NSY_Desk.php:139` returns `403 Migrations are disabled`. Use CLI.

---

## Connection

```php
Mig::connect()                 // primary (env.php connections.primary)
Mig::connect('secondary')      // secondary / custom (env.php connections.secondary)
Mig::connect('pgsql')          // pgsql / sqlsrv / dblib — via NSY_DB::connect() unified
```
> No `switch` duplication — `Mig::connect()` delegates to `NSY_DB::connect()` `System/Core/NSY_DB.php:15` (single source, `PDO::ERRMODE_EXCEPTION`).

---

## Database Helpers

```php
Mig::connect()->createDatabase(['db1', 'db2']); // CREATE DATABASE `db1`; — quoted via quoteIdent()
Mig::connect()->dropDatabase(['db1']);
Mig::connect()->dropExistTable(['t1']); // IF EXISTS
Mig::connect()->dropTable(['t1']);
```

---

## Table Helpers

```php
Mig::connect()->createTable('users', [
    Mig::bigint('id')->autoIncrement(),
    Mig::varchar('name')->notNull(),
    Mig::primary('id')
]);

Mig::connect()->renameTable('users', 'members');       // mysql: RENAME TABLE
Mig::connect()->renameTablePg('users', 'members');    // pgsql: ALTER TABLE ... RENAME TO
Mig::connect()->renameTableMs('users', 'members');    // sqlsrv: sp_rename
```

---

## Column Helpers

```php
Mig::connect()->addCols('users', [Mig::varchar('phone')->null()]);
Mig::connect()->addColsMs('users', [Mig::varchar('phone')->null()]); // sqlsrv: ADD
Mig::connect()->dropCols('users', ['phone']);
Mig::connect()->modifyCols('users', [Mig::varchar('phone')->notNull()]);
Mig::connect()->modifyColsExt('users', ['phone' => Mig::varchar('phone')->notNull()]);
Mig::connect()->renameCols('users', ['phone' => 'mobile']);
Mig::connect()->changeCols('users', ['phone' => Mig::varchar('mobile')->notNull()]);
```

---

## Indexes

```php
Mig::connect()->createTable('t', [..., Mig::primary('id')])->index('BTREE', 'name');
Mig::connect()->createTable('t', [...])->index('BTREE', ['a','b']);
Mig::connect()->createTable('t', [...])->indexPg('BTREE', 'name'); // pgsql: USING BTREE
```

Generated: `CREATE INDEX MULTI_1_5_IDX USING BTREE ON `t` ( name )`

---

## Datatypes & Modifiers

```php
Mig::bigint('id',20)->autoIncrement()
Mig::varchar('name',255)->notNull()->default('x')
Mig::text('bio')->null()
Mig::int('age')->default(0)
Mig::boolean('active')->notNull()
Mig::primary('id') / Mig::unique(['a','b'])
Mig::timestamps() // create_date/update_date/delete_date
```

Chain: `Mig::varchar('name')->notNull()->default('x')` → `name VARCHAR(255) NOT NULL DEFAULT x`

---

## Security Notes

*   Identifiers quoted via `quoteIdent()` `System/Core/NSY_Migration.php:54` → `` `table` `` per part (`db.table` safe), `sp_rename` escaped `''`.
*   `execDDL()` `System/Core/NSY_Migration.php:64` centralizes `prepare/execute`, `rollback` on `transaction==='on'`.
*   `exit()` removed — methods `return $this` chainable, no dead `stmt=null` after return.
*   HTTP migration disabled in production — use CLI.

---

## Quick Reference

| Method | Purpose | Returns |
|---|---|---|
| `Mig::connect($conn)` | Select connection (primary/secondary/...) | `object` |
| `createDatabase([db])` / `dropDatabase([db])` | CREATE/DROP DATABASE | `object` |
| `createTable($t, [cols])` / `dropTable([t])` | CREATE/DROP TABLE | `object` |
| `renameTable($old,$new)` (+ `Pg`, `Ms`) | RENAME TABLE | `object` |
| `addCols($t,[cols])` / `dropCols` / `modifyCols` | ALTER TABLE cols | `object` |
| `index($type,$cols)` / `indexPg` | CREATE INDEX | `bool` |
| `primary($cols)` / `unique($cols)` | CONSTRAINT | `string` |
| `timestamps()` | 3 DATETIME cols | `array` |

Related: `System/Core/NSY_Migration.php:37`, `System/Core/NSY_DB.php:15`, `System/Core/NSY_Migration_Route.php:8`.
