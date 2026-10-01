# NSY Seeder — User Tutorial

Seeders fill your database with data: sample records for local work, required
reference rows (roles, statuses, settings) for production, or anything that is
not schema. They are the data counterpart of [migrations](README_MIGRATION.md):
a migration changes the table, a seeder fills it.

- **Files:** `System/Seeders/<Name>.php`
- **Namespace:** `System\Seeders`
- **Contract:** a public `run()` method (extend `Seeder` for small helpers)
- **Runner:** `nsy run:seed` (CLI) or `NSY_Seeder::run()` (code)
- **Factories:** `System/Factories/<Name>.php` (dev-only, uses Faker)

## Table of Contents

1. [Anatomy of a Seeder](#anatomy-of-a-seeder)
2. [The Seeder Base Class](#the-seeder-base-class)
3. [Writing Seed Data](#writing-seed-data)
4. [Factories](#factories)
5. [Running Seeders](#running-seeders)
6. [CLI Reference](#cli-reference)
7. [Order and Idempotency](#order-and-idempotency)
8. [Quick Reference](#quick-reference)

---

## Anatomy of a Seeder

A seeder is a plain class in `System\Seeders`. The class name must match the
file basename so PSR-4 can find it.

```php
<?php

namespace System\Seeders;

class RoleSeeder extends Seeder
{
	public function run(): void
	{
		$this->table('roles')->insertBatch([
			['name' => 'admin',    'created_at' => $this->now()],
			['name' => 'editor',   'created_at' => $this->now()],
			['name' => 'customer', 'created_at' => $this->now()],
		]);
	}
}
```

Extending `Seeder` is optional — any class with a public `run()` works.

---

## The Seeder Base Class

`System\Seeders\Seeder` is abstract and adds two small helpers:

| Helper | Description | Returns |
|---|---|---|
| `run()` | Your seed logic — **you implement this** | `void` |
| `table($table, $alias = null)` | Fluent query builder for a table (same as `qb()`) | `NSY_QueryBuilder` |
| `now()` | Current timestamp in SQL format (`Y-m-d H:i:s`) | `string` |

Because `table()` returns the Query Builder, everything from the
[Query Builder guide](README_QUERY_BUILDER.md) is available: `insert()`,
`insertBatch()`, `where()`, `update()`, and so on.

---

## Writing Seed Data

```php
// Single row — insert() returns the new id (or false)
$this->table('settings')->insert([
	'key'   => 'site_name',
	'value' => 'My App',
]);

// Many rows — insertBatch() returns bool
$this->table('countries')->insertBatch([
	['code' => 'ID', 'name' => 'Indonesia'],
	['code' => 'SG', 'name' => 'Singapore'],
]);

// Update-or-insert on a natural key (keep seeders re-runnable)
$exists = $this->table('roles')->where('name', 'admin')->exists();
if (!$exists) {
	$this->table('roles')->insert(['name' => 'admin']);
}
```

> Prefer `insertBatch()` for more than a handful of rows — it wraps them in a
> single INSERT statement.

---

## Factories

A factory builds **arrays of fake attributes** for a table, so seeders stay
readable. Factories use **Faker**, which is a dev dependency — they are a
development and testing tool, not meant for production reference data.

### Define a factory

```php
// System/Factories/UserFactory.php
namespace System\Factories;

class UserFactory extends Factory
{
	public function definition(): array
	{
		return [
			'name'       => $this->faker->name(),
			'email'      => $this->faker->unique()->safeEmail(),
			'created_at' => date('Y-m-d H:i:s'),
		];
	}
}
```

### Use it

```php
factory('UserFactory');               // one row  → ['name' => …, 'email' => …, …]
factory('UserFactory', 50);           // 50 rows  → list of arrays
factory('UserFactory', 50, ['role' => 'editor']); // overrides applied to every row
factory('System\Factories\UserFactory'); // fully-qualified name also works
```

### In a seeder

```php
class UserSeeder extends Seeder
{
	public function run(): void
	{
		$this->table('users')->insertBatch(factory('UserFactory', 50));
	}
}
```

> `factory($name, 1)` returns a single row array; `$count > 1` returns a list.
> `make` / `makeMany` are also available on the factory instance directly.

---

## Running Seeders

Via the CLI (recommended):

```sh
nsy run:seed all            # every seeder, in name order
nsy run:seed list           # pick one from a list
nsy run:seed RoleSeeder     # one seeder by class name
```

From application code:

```php
use System\Core\NSY_Seeder;

NSY_Seeder::names();        // ['RoleSeeder', 'UserSeeder']  (base class skipped)
NSY_Seeder::run('RoleSeeder');
$result = NSY_Seeder::runAll(); // ['ran' => 2, 'failed' => 0]
```

`run()` returns `false` and prints the reason when the class is missing, has no
`run()`, or throws; it never lets a seeder error stop the process silently.

---

## CLI Reference

| Command | Description |
|---|---|
| `nsy make:seeder <Name>` | Create `System/Seeders/<Name>.php` from a template |
| `nsy make:factory <Name>` | Create `System/Factories/<Name>.php` from a template |
| `nsy run:seed all` | Run every seeder in `System/Seeders`, in name order |
| `nsy run:seed list` | Pick a single seeder from a numbered list |
| `nsy run:seed <Name>` | Run one seeder by class name |

The base class `Seeder.php` is never run — it holds no seed logic.

---

## Order and Idempotency

- **Order** is alphabetical by class name (`run:seed all`), so a seeder that
  depends on another should be named/structure accordingly (e.g. `01_…`, or run
  them individually in the order you need).
- **Re-runs** repeat the seed. Make a seeder idempotent when it may run more
  than once — guard with `exists()`, use an update-or-insert, or clear the table
  first (e.g. `$this->table('roles')->delete()` with no id) when that is safe.
- Seed **reference data** in production; keep **sample data** in a separate
  seeder so it is easy to skip.

---

## Quick Reference

| API | Purpose | Returns |
|---|---|---|
| `Seeder::run()` | Your seed logic | `void` |
| `Seeder::table($t,$alias)` | Query builder on a table | `NSY_QueryBuilder` |
| `Seeder::now()` | SQL timestamp | `string` |
| `NSY_Seeder::run($name)` | Run one seeder | `bool` |
| `NSY_Seeder::runAll()` | Run all seeders | `array{ran,failed}` |
| `NSY_Seeder::names()` | List seeder class names | `array<int,string>` |
| `NSY_Seeder::directory()` | Absolute path to `System/Seeders` | `string` |
| `Factory::definition()` | Fake attributes for one row | `array` |
| `Factory::make($o)` / `makeMany($n,$o)` | Build one row / many rows | `array` |
| `factory($name, $n, $o)` | Build row(s) through a factory | `array` |

Related: `System/Core/NSY_Seeder.php`, `System/Seeders/Seeder.php`,
[docs/README_MIGRATION.md](README_MIGRATION.md),
[docs/README_QUERY_BUILDER.md](README_QUERY_BUILDER.md).
