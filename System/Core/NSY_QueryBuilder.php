<?php

declare(strict_types=1);

namespace System\Core;

use System\Core\DB;

/**
 * NSY Query Builder — powerful, fluent, minimal lines
 * - Chainable: table()->where()->orderBy()->limit()->get() in 1 line
 * - Complete: whereIn/Null/Between/Like, leftJoin, pluck/exists/value, paginate, when()
 * - Minimal: DB::table() facade + global qb() helper
 */
class NSY_QueryBuilder extends DB
{
    // Class properties
    protected $pdo; // PDO instance
    protected $table; // Table name
    protected $tableAlias; // Table alias
    protected $select = '*'; // Fields to select
    protected $distinct = false; // Distinct flag
    protected $where = []; // Where clauses
    protected $join = []; // Join clauses
    protected $order = []; // Order by clauses
    protected $group = []; // Group by clauses
    protected $having = []; // Having clauses
    protected $limit; // Limit value
    protected $offset; // Offset value
    protected $bindings = []; // Query bindings

    // Constructor: Initialize the PDO instance
    public function __construct(string $conn_name = 'primary')
    {
        $this->pdo = DB::getConnection($conn_name);
    }

    /**
     * Static facade — minimal lines: NSY_QueryBuilder::table('users')->where(...)->get()
     */
    public static function tableStatic(string $table, ?string $alias = null, string $conn = 'primary'): self
    {
        $qb = new self($conn);
        return $qb->table($table, $alias);
    }

    // Set the table and optional alias
    public function table($table, $alias = null): self
    {
        $this->table = $this->quoteIdent($table);
        $this->tableAlias = $alias ? $this->quoteIdent($alias) : null;
        return $this;
    }

    // Set the fields to select
    public function select($fields): self
    {
        if (is_array($fields)) {
            $fields = implode(', ', array_map(fn($f) => $this->quoteSelect($f), $fields));
        }
        $this->select = $fields;
        return $this;
    }

    // Enable distinct selection
    public function distinct(): self
    {
        $this->distinct = true;
        return $this;
    }

    // Add a where clause
    public function where($field, $operator = '=', $value = null): self
    {
        // Allow where('id', 1) shorthand
        if (func_num_args() === 2) {
            $value = $operator;
            $operator = '=';
        }
        $prefix = empty($this->where) ? '' : 'AND ';
        $this->where[] = $prefix . $this->quoteIdent($field) . " $operator ?";
        $this->bindings[] = $value;
        return $this;
    }

    // Add multiple where clauses
    public function whereArray(array $conditions): self
    {
        foreach ($conditions as $condition) {
            if (is_array($condition) && count($condition) === 3) {
                $this->where($condition[0], $condition[1], $condition[2]);
            } elseif (is_array($condition) && count($condition) === 2) {
                $this->where($condition[0], '=', $condition[1]);
            }
        }
        return $this;
    }

    // Add an or where clause
    public function orWhere($field, $operator = '=', $value = null): self
    {
        if (func_num_args() === 2) {
            $value = $operator;
            $operator = '=';
        }
        if (empty($this->where)) {
            return $this->where($field, $operator, $value);
        }
        $this->where[] = "OR " . $this->quoteIdent($field) . " $operator ?";
        $this->bindings[] = $value;
        return $this;
    }

    // whereIn / whereNotIn
    public function whereIn(string $field, array $values): self
    {
        if (empty($values)) {
            $this->where[] = (empty($this->where) ? '' : 'AND ') . "1=0";
            return $this;
        }
        $placeholders = implode(', ', array_fill(0, count($values), '?'));
        $prefix = empty($this->where) ? '' : 'AND ';
        $this->where[] = $prefix . $this->quoteIdent($field) . " IN ($placeholders)";
        $this->bindings = array_merge($this->bindings, array_values($values));
        return $this;
    }

    public function whereNotIn(string $field, array $values): self
    {
        if (empty($values)) {
            return $this;
        }
        $placeholders = implode(', ', array_fill(0, count($values), '?'));
        $prefix = empty($this->where) ? '' : 'AND ';
        $this->where[] = $prefix . $this->quoteIdent($field) . " NOT IN ($placeholders)";
        $this->bindings = array_merge($this->bindings, array_values($values));
        return $this;
    }

    public function orWhereIn(string $field, array $values): self
    {
        if (empty($values)) {
            return $this;
        }
        $placeholders = implode(', ', array_fill(0, count($values), '?'));
        $this->where[] = "OR " . $this->quoteIdent($field) . " IN ($placeholders)";
        $this->bindings = array_merge($this->bindings, array_values($values));
        return $this;
    }

    // whereNull / whereNotNull
    public function whereNull(string $field): self
    {
        $prefix = empty($this->where) ? '' : 'AND ';
        $this->where[] = $prefix . $this->quoteIdent($field) . " IS NULL";
        return $this;
    }

    public function whereNotNull(string $field): self
    {
        $prefix = empty($this->where) ? '' : 'AND ';
        $this->where[] = $prefix . $this->quoteIdent($field) . " IS NOT NULL";
        return $this;
    }

    // whereBetween / whereNotBetween
    public function whereBetween(string $field, array $range): self
    {
        if (count($range) !== 2) {
            return $this;
        }
        $prefix = empty($this->where) ? '' : 'AND ';
        $this->where[] = $prefix . $this->quoteIdent($field) . " BETWEEN ? AND ?";
        $this->bindings[] = $range[0];
        $this->bindings[] = $range[1];
        return $this;
    }

    public function whereNotBetween(string $field, array $range): self
    {
        if (count($range) !== 2) {
            return $this;
        }
        $prefix = empty($this->where) ? '' : 'AND ';
        $this->where[] = $prefix . $this->quoteIdent($field) . " NOT BETWEEN ? AND ?";
        $this->bindings[] = $range[0];
        $this->bindings[] = $range[1];
        return $this;
    }

    // whereLike / whereNotLike
    public function whereLike(string $field, string $value): self
    {
        $prefix = empty($this->where) ? '' : 'AND ';
        $this->where[] = $prefix . $this->quoteIdent($field) . " LIKE ?";
        $this->bindings[] = $value;
        return $this;
    }

    public function whereNotLike(string $field, string $value): self
    {
        $prefix = empty($this->where) ? '' : 'AND ';
        $this->where[] = $prefix . $this->quoteIdent($field) . " NOT LIKE ?";
        $this->bindings[] = $value;
        return $this;
    }

    // Conditional when()
    public function when(mixed $condition, callable $callback, ?callable $default = null): self
    {
        if ($condition) {
            $callback($this);
        } elseif ($default) {
            $default($this);
        }
        return $this;
    }

    // Add a join clause with optional alias
    public function join($table, $field1, $operator, $field2, $type = 'INNER', $alias = null): self
    {
        $joinClause = $type . ' JOIN ' . $this->quoteIdent($table);
        if ($alias) {
            $joinClause .= ' AS ' . $this->quoteIdent($alias);
        }
        $joinClause .= ' ON ' . $this->quoteIdent($field1) . ' ' . $operator . ' ' . $this->quoteIdent($field2);
        $this->join[] = $joinClause;
        return $this;
    }

    public function leftJoin($table, $field1, $operator, $field2, $alias = null): self
    {
        return $this->join($table, $field1, $operator, $field2, 'LEFT', $alias);
    }

    public function rightJoin($table, $field1, $operator, $field2, $alias = null): self
    {
        return $this->join($table, $field1, $operator, $field2, 'RIGHT', $alias);
    }

    public function crossJoin($table, $alias = null): self
    {
        $clause = 'CROSS JOIN ' . $this->quoteIdent($table);
        if ($alias) {
            $clause .= ' AS ' . $this->quoteIdent($alias);
        }
        $this->join[] = $clause;
        return $this;
    }

    // Add an order by clause
    public function orderBy($field, $direction = 'ASC'): self
    {
        $this->order[] = $this->quoteIdent($field) . " " . strtoupper($direction);
        return $this;
    }

    // Add a group by clause
    public function groupBy($field): self
    {
        $this->group[] = $this->quoteIdent($field);
        return $this;
    }

    // Add a having clause
    public function having($field, $operator, $value): self
    {
        $this->having[] = $this->quoteIdent($field) . " $operator ?";
        $this->bindings[] = $value;
        return $this;
    }

    // Set the limit and offset
    public function limit($limit, $offset = 0): self
    {
        $this->limit = (int) $limit;
        $this->offset = (int) $offset;
        return $this;
    }

    public function offset($offset): self
    {
        $this->offset = (int) $offset;
        return $this;
    }

    // Build base SELECT SQL
    private function buildSelect(): string
    {
        $sql = "SELECT ";
        if ($this->distinct) {
            $sql .= "DISTINCT ";
        }
        $sql .= "{$this->select} FROM {$this->table}";
        if ($this->tableAlias) {
            $sql .= " AS {$this->tableAlias}";
        }
        if ($this->join) {
            $sql .= ' ' . implode(' ', $this->join);
        }
        if ($this->where) {
            $sql .= " WHERE " . implode(' ', $this->where);
        }
        if ($this->group) {
            $sql .= " GROUP BY " . implode(', ', $this->group);
        }
        if ($this->having) {
            $sql .= " HAVING " . implode(' AND ', $this->having);
        }
        if ($this->order) {
            $sql .= " ORDER BY " . implode(', ', $this->order);
        }
        return $sql;
    }

    // Build and execute the select query
    public function get(): array
    {
        $sql = $this->buildSelect();
        if ($this->limit) {
            $sql .= " LIMIT " . (int)$this->limit;
        }
        if ($this->offset) {
            $sql .= " OFFSET " . (int)$this->offset;
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->bindings);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Get the first result
    public function first(): ?array
    {
        $this->limit(1);
        $results = $this->get();
        return !empty($results) ? $results[0] : null;
    }

    // Pluck single column
    public function pluck(string $column): array
    {
        $origSelect = $this->select;
        $this->select = $this->quoteIdent($column);
        $rows = $this->get();
        $this->select = $origSelect;
        return array_column($rows, $column);
    }

    // Get single value
    public function value(string $column): mixed
    {
        $row = $this->first();
        return $row[$column] ?? null;
    }

    // Exists check
    public function exists(): bool
    {
        $sql = "SELECT 1 FROM {$this->table}";
        if ($this->tableAlias) {
            $sql .= " AS {$this->tableAlias}";
        }
        if ($this->join) {
            $sql .= ' ' . implode(' ', $this->join);
        }
        if ($this->where) {
            $sql .= " WHERE " . implode(' ', $this->where);
        }
        $sql .= " LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->bindings);
        return (bool) $stmt->fetchColumn();
    }

    // Aggregates
    public function count(?string $column = null): int
    {
        $col = $column ? $this->quoteIdent($column) : '*';
        $sql = "SELECT COUNT($col) as count FROM {$this->table}";
        if ($this->tableAlias) {
            $sql .= " AS {$this->tableAlias}";
        }
        if ($this->join) {
            $sql .= ' ' . implode(' ', $this->join);
        }
        if ($this->where) {
            $sql .= " WHERE " . implode(' ', $this->where);
        }
        if ($this->group) {
            $sql .= " GROUP BY " . implode(', ', $this->group);
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->bindings);
        return (int) $stmt->fetchColumn();
    }

    public function sum(string $column): mixed
    {
        return $this->aggregate('SUM', $column);
    }

    public function avg(string $column): mixed
    {
        return $this->aggregate('AVG', $column);
    }

    public function max(string $column): mixed
    {
        return $this->aggregate('MAX', $column);
    }

    public function min(string $column): mixed
    {
        return $this->aggregate('MIN', $column);
    }

    private function aggregate(string $func, string $column): mixed
    {
        $sql = "SELECT $func(" . $this->quoteIdent($column) . ") as agg FROM {$this->table}";
        if ($this->where) {
            $sql .= " WHERE " . implode(' ', $this->where);
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->bindings);
        return $stmt->fetchColumn();
    }

    // Pagination — minimal lines: paginate(15) returns data + meta
    public function paginate(int $perPage = 15, int $page = 1): array
    {
        $total = $this->count();
        $this->limit($perPage, ($page - 1) * $perPage);
        $data = $this->get();
        return [
            'data' => $data,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => (int) ceil($total / $perPage),
        ];
    }

    // Insert a new record
    public function insert(array $data): string|false
    {
        $columns = implode(', ', array_map(fn($c) => $this->quoteIdent($c), array_keys($data)));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array_values($data));
        return $this->pdo->lastInsertId();
    }

    public function insertBatch(array $rows): bool
    {
        if (empty($rows)) {
            return false;
        }
        $columns = array_keys($rows[0]);
        $colStr = implode(', ', array_map(fn($c) => $this->quoteIdent($c), $columns));
        $placeholder = '(' . implode(', ', array_fill(0, count($columns), '?')) . ')';
        $allPlaceholders = implode(', ', array_fill(0, count($rows), $placeholder));
        $sql = "INSERT INTO {$this->table} ($colStr) VALUES $allPlaceholders";
        $bindings = [];
        foreach ($rows as $row) {
            $bindings = array_merge($bindings, array_values($row));
        }
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($bindings);
    }

    // Update — chainable where()->update([...]) or legacy update([...], $idField, $idValue)
    public function update(array $data, $idField = null, $idValue = null): int
    {
        if ($idField !== null && $idValue !== null) {
            $this->where($idField, '=', $idValue);
        } elseif (empty($this->where)) {
            throw new \InvalidArgumentException('Update requires where() or idField/idValue');
        }
        $setClause = implode(', ', array_map(fn($col) => $this->quoteIdent($col) . " = ?", array_keys($data)));
        $sql = "UPDATE {$this->table} SET $setClause WHERE " . implode(' ', $this->where);
        $bindings = array_merge(array_values($data), $this->bindings);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($bindings);
        return $stmt->rowCount();
    }

    // Delete — chainable where()->delete() or legacy delete($field,$value)
    public function delete($idField = null, $idValue = null): int
    {
        if ($idField !== null && $idValue !== null) {
            $this->where($idField, '=', $idValue);
        } elseif (empty($this->where)) {
            throw new \InvalidArgumentException('Delete requires where() or idField/idValue');
        }
        $sql = "DELETE FROM {$this->table} WHERE " . implode(' ', $this->where);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->bindings);
        return $stmt->rowCount();
    }

    // Debug helpers
    public function toSql(): string
    {
        $sql = $this->buildSelect();
        if ($this->limit) {
            $sql .= " LIMIT " . (int)$this->limit;
        }
        if ($this->offset) {
            $sql .= " OFFSET " . (int)$this->offset;
        }
        return $sql;
    }

    public function getBindings(): array
    {
        return $this->bindings;
    }

    public function dd(): never
    {
        var_dump($this->toSql(), $this->bindings);
        exit();
    }

    // Increment / decrement
    public function increment(string $column, int $amount = 1): int
    {
        if (empty($this->where)) {
            throw new \InvalidArgumentException('Increment requires where()');
        }
        $sql = "UPDATE {$this->table} SET " . $this->quoteIdent($column) . " = " . $this->quoteIdent($column) . " + ? WHERE " . implode(' ', $this->where);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array_merge([$amount], $this->bindings));
        return $stmt->rowCount();
    }

    public function decrement(string $column, int $amount = 1): int
    {
        return $this->increment($column, -$amount);
    }

    // Quote helpers
    private function quoteIdent(string $ident): string
    {
        // Handle table.column or alias, keep * and functions
        if ($ident === '*' || str_contains($ident, '(') || str_contains($ident, ' ')) {
            return $ident;
        }
        $parts = explode('.', $ident);
        $quoted = array_map(fn($p) => '`' . str_replace('`', '``', trim($p, ' `')) . '`', $parts);
        return implode('.', $quoted);
    }

    private function quoteSelect(string $field): string
    {
        // Handle "col as alias" or "table.col"
        if (preg_match('/\s+as\s+/i', $field)) {
            [$col, $alias] = preg_split('/\s+as\s+/i', $field);
            return $this->quoteIdent(trim($col)) . ' AS ' . $this->quoteIdent(trim($alias));
        }
        return $this->quoteIdent($field);
    }

    // Execute a raw SQL query
    public function raw($sql, $bindings = []): array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($bindings);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}

// Global helper — minimal lines: qb('users')->where(...)->get()
if (!function_exists('qb')) {
    function qb(string $table, ?string $alias = null, string $conn = 'primary'): \System\Core\NSY_QueryBuilder
    {
        return (new \System\Core\NSY_QueryBuilder($conn))->table($table, $alias);
    }
}
