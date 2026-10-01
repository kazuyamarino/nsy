# NSY Migration — User Tutorial

Database versioning for NSY (`System/Core/NSY_Migration.php`, alias `Mig`). Powerful after refactor: unified `NSY_DB::connect()`, DRY `execDDL()` + `quoteIdent()` (backtick per identifier), chainable `return $this`, `declare(strict_types=1)`.

- **Migration class:** `System\Migrations\*` (created via CLI)
- **Engine:** `System\Core\NSY_Migration` (`Mig`) — DDL only (vs `System\Core\DB` for DML)
- **Route guard:** `System/Core/NSY_Migration_Route.php` — the HTTP trigger (`/migup=(:any)`, `/migdown=(:any)`) is **off by default**: it registers only when `APP_ENV === 'development'` **and** `APP_MIGRATION_HTTP=true`, with a non-empty `APP_MIGRATION_HTTP_TOKEN` passed as `?token=…`. Production is always blocked (`NSY_Desk` returns 403).

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
# → System/Migrations/create_supplier_table_01102026_120000.php
#   (the class name is the file basename; the generator appends a timestamp)
```

```php
<?php
use System\Core\NSY_Migration as Mig;

class create_supplier_table_01102026_120000 {
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

> Prefer a descriptive table name (`suppliers`) instead of reusing the migration
> name; the generated file ships with a `your_table` placeholder.

---

## Running Migrations

**CLI (recommended, production-safe):**
```bash
nsy show:migrate          # list
nsy run:migrate all       # all pending
nsy run:migrate list      # pick one
```

**HTTP (development only, opt-in):**

Enable it in `env.php`, then pass the token as `?token=…`:

```ini
APP_MIGRATION_HTTP=true
APP_MIGRATION_HTTP_TOKEN=change-me
```

```
GET /migup=create_supplier_table_01102026_120000?token=change-me
GET /migdown=create_supplier_table_01102026_120000?token=change-me
```
> Without `APP_MIGRATION_HTTP=true` plus a non-empty token the trigger is not even
> registered (404). In `production` `NSY_Desk` returns `403 Migrations are disabled`.
> Prefer the CLI.

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

Generated:

```sql
CREATE INDEX MULTI_123456_IDX USING BTREE ON `t` ( `name` )
```

---

## Datatypes & Modifiers

All type builders are static, take the column name first, and may be followed by a single `->modifier()` (see [Modifiers](#datatypes--modifiers)).

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

**Modifiers** — each column takes **at most one** modifier. A modifier returns the
finished column definition (a string), so it is **not chainable**. `default()`
already includes `NOT NULL`:

```php
Mig::varchar('name', 255)->notNull()                 // name VARCHAR(255) NOT NULL
Mig::int('age')->default(0)                          // age INT(11) NOT NULL DEFAULT 0
Mig::text('bio')->null()                             // bio TEXT NULL
Mig::bigint('id', 20)->autoIncrement()               // id BIGINT(20) AUTO_INCREMENT
Mig::datetime('updated')->onUpdate('CURRENT_TIMESTAMP')
```

> String defaults must be SQL-quoted yourself: `->default("'active'")`.
> Do **not** write `->notNull()->default('x')` — a second modifier fails with
> "Call to a member function … on string".

**Constraints & records**

```php
Mig::primary('id')       // PRIMARY KEY
Mig::unique(['a', 'b'])  // UNIQUE
Mig::connect()->insertRecord('users', ['name' => 'Alice', 'age' => 30])
```

Result: `Mig::varchar('name')->notNull()` → `name VARCHAR(255) NOT NULL`;
`Mig::int('age')->default(0)` → `age INT(11) NOT NULL DEFAULT 0`.

---

## Security Notes

*   Identifiers quoted via `quoteIdent()` `System/Core/NSY_Migration.php:54` → `` `table` `` per part (`db.table` safe), `sp_rename` escaped `''`.
*   `execDDL()` `System/Core/NSY_Migration.php:64` centralizes `prepare/execute`, `rollback` on `transaction==='on'`.
*   `exit()` removed — methods `return $this` chainable, no dead `stmt=null` after return.
*   HTTP migration is opt-in (`APP_MIGRATION_HTTP`) + token-guarded (`?token=`) and disabled in production — use CLI.

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
| `->notNull()` / `->null()` / `->default()` / `->autoIncrement()` / `->onUpdate()` | Column modifier — **one per column** (not chainable) | `string` |
| `insertRecord($t, $data)` | Insert one row (DML) | `object` |

Related: `System/Core/NSY_Migration.php:27`, `System/Core/NSY_DB.php:24`, `System/Core/NSY_Migration_Route.php:8`.
