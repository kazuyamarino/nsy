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
> No `switch` duplication — `Mig::connect()` delegates to `NSY_DB::connect()` `System/Core/NSY_DB.php:24` (single source, `PDO::ERRMODE_EXCEPTION`). Driver matrix and `env.php` keys: see [`README_MODEL.md`](README_MODEL.md#supported-drivers).

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
Mig::connect()->renameColsMs('users', ['phone' => 'mobile']); // sqlsrv: sp_rename
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

All type builders are static, take the column name first, and are chainable.

**Integer & boolean**

```php
Mig::bit('flag')                  // BIT
Mig::tinyint('a', 4)              // TINYINT(4)
Mig::smallint('a', 5)             // SMALLINT(5)
Mig::mediumint('a', 9)            // MEDIUMINT(9)
Mig::int('a', 11)                 // INT(11)       (alias: Mig::integer)
Mig::bigint('a', 20)              // BIGINT(20)
Mig::bool('active')               // BOOL          (alias: Mig::boolean)
```

**Decimal & floating point**

```php
Mig::decimal('price', 10, 2)         // DECIMAL(10,2)  (aliases: Mig::dec, Mig::numeric)
Mig::fixed('price', 10, 2)           // FIXED(10,2)
Mig::float('score', 10, 2)           // FLOAT(10,2)
Mig::floatPrecision('score', 4)      // FLOAT(4)
Mig::double('rate', 10, 2)           // DOUBLE(10,2)
Mig::doublePrecision('rate', 10, 2)  // DOUBLE PRECISION(10,2)
Mig::real('rate', 10, 2)             // REAL(10,2)
```

**String & text**

```php
Mig::char('code', 10)     // CHAR(10)
Mig::varchar('name', 255) // VARCHAR(255)
Mig::tinytext('bio')      // TINYTEXT
Mig::text('bio')          // TEXT
Mig::mediumtext('bio')    // MEDIUMTEXT
Mig::longtext('bio')      // LONGTEXT
```

**Binary**

```php
Mig::binary('avatar', 255)  // BINARY(255)
Mig::varbinary('token', 255) // VARBINARY(255)
Mig::tinyblob('thumb')      // TINYBLOB
Mig::blob('file')           // BLOB
Mig::mediumblob('file')     // MEDIUMBLOB
```

**Date & time**

```php
Mig::date('born')            // DATE
Mig::datetime('created')     // DATETIME
Mig::timestamp('updated', 6) // TIMESTAMP(6)
Mig::time('slot')            // TIME
Mig::year('yr', 2)           // YEAR(2)
Mig::timestamps()            // create_date / update_date / delete_date (3 DATETIME)
```

**Modifiers** (chain after any type)

```php
Mig::varchar('name', 255)->notNull()->default('x')
Mig::int('age')->default(0)
Mig::text('bio')->null()
Mig::bigint('id', 20)->autoIncrement()
Mig::datetime('updated')->onUpdate('CURRENT_TIMESTAMP')
```

**Constraints & records**

```php
Mig::primary('id')       // PRIMARY KEY
Mig::unique(['a', 'b'])  // UNIQUE
Mig::connect()->insertRecord('users', ['name' => 'Alice', 'age' => 30])
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
| `Mig::{type}($col, ...)` | Column type builder ([full list](#datatypes--modifiers)) | `object` |
| `->notNull()` / `->null()` / `->default()` / `->autoIncrement()` / `->onUpdate()` | Column modifiers (chainable) | `object` |
| `insertRecord($t, $data)` | Insert one row (DML) | `object` |

Related: `System/Core/NSY_Migration.php:27`, `System/Core/NSY_DB.php:24`, `System/Core/NSY_Migration_Route.php:8`.
