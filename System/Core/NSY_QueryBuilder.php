<?php

namespace System\Core;

use System\Core\DB;

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

    // Set the table and optional alias
    public function table($table, $alias = null)
    {
        $this->table = $table;
        $this->tableAlias = $alias;
        return $this;
    }

    // Set the fields to select
    public function select($fields)
    {
        $this->select = is_array($fields) ? implode(', ', $fields) : $fields;
        return $this;
    }

    // Enable distinct selection
    public function distinct()
    {
        $this->distinct = true;
        return $this;
    }

    // Add a where clause
    public function where($field, $operator, $value)
    {
        $this->where[] = "$field $operator ?";
        $this->bindings[] = $value;
        return $this;
    }

    // Add multiple where clauses
    public function whereArray(array $conditions)
    {
        foreach ($conditions as $condition) {
            if (is_array($condition) && count($condition) === 3) {
                $this->where($condition[0], $condition[1], $condition[2]);
            }
        }
        return $this;
    }

    // Add an or where clause
    public function orWhere($field, $operator, $value)
    {
        if (empty($this->where)) {
            return $this->where($field, $operator, $value);
        }

        $this->where[] = "OR $field $operator ?";
        $this->bindings[] = $value;
        return $this;
    }

    // Add a join clause with optional alias
    public function join($table, $field1, $operator, $field2, $type = 'INNER', $alias = null)
    {
        $joinClause = $type . ' JOIN ' . $table;
        if ($alias) {
            $joinClause .= ' AS ' . $alias;
        }
        $joinClause .= ' ON ' . $field1 . ' ' . $operator . ' ' . $field2;
        $this->join[] = $joinClause;
        return $this;
    }

    // Add an order by clause
    public function orderBy($field, $direction = 'ASC')
    {
        $this->order[] = "$field $direction";
        return $this;
    }

    // Add a group by clause
    public function groupBy($field)
    {
        $this->group[] = $field;
        return $this;
    }

    // Add a having clause
    public function having($field, $operator, $value)
    {
        $this->having[] = "$field $operator ?";
        $this->bindings[] = $value;
        return $this;
    }

    // Set the limit and offset
    public function limit($limit, $offset = 0)
    {
        $this->limit = $limit;
        $this->offset = $offset;
        return $this;
    }

    // Build and execute the select query
    public function get()
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
    public function first()
    {
        $this->limit(1);
        $results = $this->get();
        return !empty($results) ? $results[0] : null;
    }

    // Insert a new record
    public function insert(array $data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array_values($data));
        return $this->pdo->lastInsertId();
    }

    // Update an existing record
    public function update(array $data, $idField, $idValue)
    {
        $setClause = implode(', ', array_map(fn($col) => "$col = ?", array_keys($data)));
        $sql = "UPDATE {$this->table} SET $setClause WHERE $idField = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array_merge(array_values($data), [$idValue]));
        return $stmt->rowCount();
    }

    // Delete a record
    public function delete($idField, $idValue)
    {
        $sql = "DELETE FROM {$this->table} WHERE $idField = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$idValue]);
        return $stmt->rowCount();
    }

    // Count the number of records
    public function count()
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
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

        return $stmt->fetchColumn();
    }

    // Execute a raw SQL query
    public function raw($sql, $bindings = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($bindings);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
