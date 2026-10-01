# NSY Query Builder — User Tutorial

Fluent, powerful query builder for NSY (`System/Core/NSY_QueryBuilder.php`). One line per query, no boilerplate.

- **Engine:** `System\Core\NSY_QueryBuilder extends DB` — reuses `DB::getConnection()` (`NSY_DB::connect()` unified)
- **Facade:** `NSY_QueryBuilder::tableStatic('users')` or global `qb('users')` — vs `new NSY_QueryBuilder()->table('users')` (all equivalent)
- **Secure:** All values bound via `?` placeholders, identifiers quoted with backticks

## Table of Contents

1. [Quick Start](#quick-start)
2. [Selecting](#selecting)
3. [Filtering — where](#filtering--where)
4. [Filtering — whereIn / Null / Between / Like](#filtering--wherein--null--between--like)
5. [Joins](#joins)
6. [Ordering, Grouping, Having, Limit](#ordering-grouping-having-limit)
7. [Fetching — get / first / pluck / value / exists](#fetching--get--first--pluck--value--exists)
8. [Aggregates — count / sum / avg / min / max](#aggregates--count--sum--avg--min--max)
9. [Pagination](#pagination)
10. [Mutations — insert / insertBatch / update / delete / increment](#mutations--insert--insertbatch--update--delete--increment)
11. [Conditional — when()](#conditional--when)
12. [Raw & Debug](#raw--debug)
13. [Complete Examples — Minimal Lines](#complete-examples--minimal-lines)
14. [Quick Reference](#quick-reference)

---

## Quick Start

```php
use System\Core\NSY_QueryBuilder;

// All three are identical — pick the shortest
$users = (new NSY_QueryBuilder())->table('users')->where('active', 1)->get();
$users = NSY_QueryBuilder::tableStatic('users')->where('active', 1)->get();
$users = qb('users')->where('active', 1)->get(); // global helper — minimal

// With connection
$users = qb('users', null, 'secondary')->where('id', 1)->first();
```

---

## Selecting

```php
qb('users')->select('*')->get();
qb('users')->select(['id', 'name'])->get();
qb('users')->select(['id as user_id', 'name'])->get();
qb('users')->distinct()->select(['country'])->get();
// SELECT DISTINCT `country` FROM `users`
```

---

## Filtering — where

```php
qb('users')->where('id', '=', 1)->get();
qb('users')->where('id', 1)->get(); // shorthand: operator defaults to =
qb('users')->where('age', '>', 18)->get();
qb('users')->whereArray([['active', '=', 1], ['age', '>', 18]])->get();
qb('users')->where('active', 1)->orWhere('role', 'admin')->get();
// WHERE `active` = ? AND `role` = ? OR `role` = ?
```

---

## Filtering — whereIn / Null / Between / Like

```php
qb('users')->whereIn('id', [1,2,3])->get();
// WHERE `id` IN (?, ?, ?)

qb('users')->whereNotIn('status', ['banned'])->get();
qb('users')->whereNull('deleted_at')->get();
qb('users')->whereNotNull('email')->get();
qb('users')->whereBetween('age', [18, 30])->get();
qb('users')->whereNotBetween('age', [0, 10])->get();
qb('users')->whereLike('name', '%john%')->get();
qb('users')->whereNotLike('name', '%test%')->get();
```

---

## Joins

```php
qb('users')->join('posts', 'posts.user_id', '=', 'users.id')->get();
qb('users')->leftJoin('posts', 'posts.user_id', '=', 'users.id')->get();
qb('users')->rightJoin('posts', 'posts.user_id', '=', 'users.id')->get();
qb('users')->crossJoin('settings')->get();
qb('users')->join('posts', 'posts.user_id', '=', 'users.id', 'INNER', 'p')->get(); // alias
```

---

## Ordering, Grouping, Having, Limit

```php
qb('users')->orderBy('name', 'DESC')->get();
qb('users')->groupBy('country')->having('count', '>', 10)->get();
qb('users')->limit(10)->get();
qb('users')->limit(10, 20)->get(); // limit 10 offset 20
qb('users')->offset(20)->limit(10)->get();
```

---

## Fetching — get / first / pluck / value / exists

```php
qb('users')->where('active',1)->get();          // array of rows
qb('users')->where('id',1)->first();            // single row or null
qb('users')->where('active',1)->pluck('name');  // ['Alice','Bob']
qb('users')->where('id',1)->value('name');      // 'Alice'
qb('users')->where('email','a@b.com')->exists(); // bool
```

---

## Aggregates — count / sum / avg / min / max

```php
qb('users')->count(); // SELECT COUNT(*) 
qb('users')->count('id');
qb('users')->where('active',1)->count();
qb('users')->sum('balance');
qb('users')->avg('age');
qb('users')->max('age');
qb('users')->min('age');
```

---

## Pagination

```php
$page = qb('users')->where('active',1)->paginate(15, 1);
// [
//   'data' => [...], 'total' => 42, 'per_page' => 15,
//   'current_page' => 1, 'last_page' => 3
// ]

foreach ($page['data'] as $user) { echo $user['name']; }
echo "Page {$page['current_page']} of {$page['last_page']}";
```

### Rendering the links

`paginate()` returns the rows plus meta; render the controls with
`paginate_links()`:

```php
$page = qb('users')->where('active', 1)->paginate(15, (int) ($_GET['page'] ?? 1));
echo paginate_links($page);
// <nav class="nsy-pagination" aria-label="Pagination"> … </nav>
```

- Preserves the current query string (minus the page param) so filters and
  sorting survive page navigation.
- Options: `page_param` (default `page`), `window` (default `2`), `class`
  (default `nsy-pagination`), `path`, `query`.
- Returns `''` when there is at most one page (nothing to render).
- First/Last and Prev/Next are always present; disabled ones render as
  `<span … is-disabled>`.
- Default styling ships in `public/assets/css/main.css` (`.nsy-pagination`).

```php
echo paginate_links($page, ['window' => 1, 'page_param' => 'p']);
```

---

## Mutations — insert / insertBatch / update / delete / increment

```php
// Insert
$id = qb('users')->insert(['name' => 'Ana', 'email' => 'a@b.com']);

// Batch insert — 1 query
qb('users')->insertBatch([
    ['name' => 'A', 'email' => 'a@b.com'],
    ['name' => 'B', 'email' => 'b@b.com'],
]);

// Update — chainable where (BC: also supports legacy update($data,'id',1))
qb('users')->where('id', 1)->update(['name' => 'Ana']);
qb('users')->where('active', 0)->update(['active' => 1]);

// Delete — chainable
qb('users')->where('id', 1)->delete();
qb('users')->where('deleted_at', '!=', null)->delete(); // via whereNull etc also

// Increment / decrement
qb('users')->where('id', 1)->increment('views');
qb('users')->where('id', 1)->increment('views', 5);
qb('users')->where('id', 1)->decrement('credits', 10);
```

---

## Conditional — when()

```php
$search = $_GET['q'] ?? null;
$role = $_GET['role'] ?? null;

qb('users')
    ->when($search, fn($q) => $q->whereLike('name', "%$search%"))
    ->when($role, fn($q) => $q->where('role', $role), fn($q) => $q->where('active', 1))
    ->get();
```

---

## Raw & Debug

```php
qb('users')->raw("SELECT * FROM users WHERE id = ?", [1]);

// Debug
qb('users')->where('id',1)->toSql(); // SELECT * FROM `users` WHERE `id` = ?
qb('users')->where('id',1)->getBindings(); // [1]
qb('users')->where('id',1)->dd(); // var_dump sql + bindings + exit
```

---

## Complete Examples — Minimal Lines

```php
// 1 line: active users with posts, paginated
qb('users')->where('active',1)->whereNull('deleted_at')->leftJoin('posts','posts.user_id','=','users.id')->orderBy('name')->paginate(10);

// 1 line: search + filter
qb('products')->whereLike('name','%phone%')->whereBetween('price',[100,500])->whereIn('status',['active'])->get();

// 1 line: update via where chain (previously required idField/idValue)
qb('users')->where('email','a@b.com')->update(['active'=>1]);

// 1 line: pluck names
$names = qb('users')->where('active',1)->pluck('name');

// 1 line: check exists
if (qb('users')->where('email',$email)->exists()) { /* ... */ }
```

---

## Quick Reference

| Method | Purpose | Returns |
|---|---|---|
| `qb('table')` / `NSY_QueryBuilder::tableStatic('t')` | Start query | `self` |
| `table('t','alias')` / `select(['id'])` / `distinct()` | From / columns | `self` |
| `where('f','=',1)` / `orWhere` / `whereArray` | Basic filter | `self` |
| `whereIn('id',[1,2])` / `whereNotIn` / `orWhereIn` | IN filter | `self` |
| `whereNull('f')` / `whereNotNull` | Null check | `self` |
| `whereBetween('f',[a,b])` / `whereNotBetween` | Between | `self` |
| `whereLike('f','%a%')` / `whereNotLike` | LIKE | `self` |
| `when($cond, fn($q)=>...)` | Conditional | `self` |
| `join('t','a','=','b')` / `leftJoin` / `rightJoin` / `crossJoin` | Joins | `self` |
| `orderBy('f','DESC')` / `groupBy` / `having` / `limit(10,5)` | Clause | `self` |
| `get()` / `first()` | Fetch | `array` / `?array` |
| `pluck('name')` / `value('name')` / `exists()` | Single column/value/check | `array/mixed/bool` |
| `count()` / `sum('col')` / `avg` / `max` / `min` | Aggregate | `int/mixed` |
| `paginate(15,1)` | Pagination | `array{data,total,...}` |
| `paginate_links($page)` | Render pagination nav | `string` |
| `insert([...])` / `insertBatch([[...]])` | Insert | `string/false` / `bool` |
| `where(...)->update([...])` / `where(...)->delete()` | Update/delete via chain | `int` (rowCount) |
| `increment('col',1)` / `decrement` | Counter | `int` |
| `raw($sql,$bindings)` / `toSql()` / `getBindings()` / `dd()` | Raw/debug | `array/string` |

Related: `System/Core/NSY_QueryBuilder.php:10`, `System/Core/DB.php` (`getConnection()`), `System/Core/NSY_DB.php` (unified).
