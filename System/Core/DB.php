<?php

declare(strict_types=1);

namespace System\Core;

use System\Libraries\Log\LogManager;

/**
 * This is the core of NSY Model
 * Attention, don't try to change the structure of the code, delete, or change.
 * Because there is some code connected to the NSY system. So, be careful.
 */
class DB
{

    // Declare properties for Helper
    protected static $connection;
    protected static $query;
    protected static $variables;
    protected static $fetch_style;
    protected static $bind;
    protected static $column;
    protected static $bind_name;
    protected static $attr;
    protected static $param;
    protected static $num;
    protected static $result;
    protected static $executed;

    /** Slow-query instrumentation: query start time and normalised SQL. */
    private static float $queryStart = 0.0;
    private static string $querySql = '';

    private static function beginQueryLog(): void
    {
        self::$queryStart = microtime(true);
        self::$querySql = (string) static::$query;
    }

    private static function endQueryLog(): void
    {
        if (self::$queryStart <= 0.0) {
            return;
        }

        LogManager::query(self::$querySql, (microtime(true) - self::$queryStart) * 1000.0);
        self::$queryStart = 0.0;
    }

    /**
     * Bind the current variables to a prepared statement and execute it.
     * Covers the legacy bind modes (BINDVALUE / BINDPARAM) and plain execute,
     * so every read/write helper shares one implementation.
     *
     * @param  \PDOStatement $stmt
     * @return bool  The execute() result
     */
    private static function bindAndExecute(\PDOStatement $stmt): bool
    {
        if (not_filled(static::$variables)) {
            $result = $stmt->execute();
        } elseif (not_filled(static::$bind)) {
            $result = $stmt->execute(static::$variables);
        } elseif (static::$bind === 'BINDVALUE') {
            self::bindAll($stmt, 'bindValue');
            $result = $stmt->execute();
        } elseif (static::$bind === 'BINDPARAM') {
            self::bindAll($stmt, 'bindParam');
            $result = $stmt->execute();
        } else {
            $var_msg = "The value that binds in the <mark>bind(<strong>value</strong>)</mark> is empty, undefined, or unknown parameter";
            NSY_Desk::staticErrorHandler($var_msg);
        }

        // The legacy API keeps state in static properties; clear the per-query
        // bindings so a later query cannot reuse stale variables/bind mode.
        static::$variables = [];
        static::$bind = '';

        return $result;
    }

    /**
     * Bind every variable in the legacy [value, PDO_type] shape.
     *
     * @param  \PDOStatement $stmt
     * @param  string        $method 'bindValue' or 'bindParam'
     */
    private static function bindAll(\PDOStatement $stmt, string $method): void
    {
        if (!is_array(static::$variables) && !is_object(static::$variables)) {
            return;
        }

        $label = $method === 'bindValue' ? 'BindValue' : 'BindParam';

        foreach (static::$variables as $key => &$res) {
            if (not_filled($res[1]) || not_filled($res[0])) {
                $var_msg = $label . ' parameter type undefined, for example use PAR_INT or PAR_STR in the <strong>null</strong> variable.<br><br>[' . $key . ' => [' . $res[0] . ', <strong>null</strong>] ]';
                NSY_Desk::staticErrorHandler($var_msg);
                continue;
            }

            if ($method === 'bindValue') {
                $stmt->bindValue($key, $res[0], $res[1]);
            } else {
                $stmt->bindParam($key, $res[0], $res[1]);
            }
        }
        unset($res);
    }

    /**
     * Run $work inside a transaction when config('transaction') is 'on'.
     * Rolls back and reports on failure; runs plainly when 'off'.
     *
     * @param  callable $work
     */
    private static function withTransaction(callable $work): void
    {
        $mode = config_app('transaction');

        if ($mode === 'on') {
            try {
                static::$connection->beginTransaction();
                $work();
                static::$connection->commit();
            } catch (\PDOException $e) {
                static::$connection->rollBack();
                NSY_Desk::staticErrorHandler('Database query failed: ' . $e->getMessage(), 500);
            }
            return;
        }

        if ($mode === 'off') {
            $work();
            return;
        }

        NSY_Desk::staticErrorHandler('The transaction mode is not set correctly. Check System/Config/App.php.', 500);
    }

    /**
     * Default Connection
     *
     * @param string $conn_name
     * @return object
     */
    protected static function connect(string $conn_name = 'primary'): object
    {
        // Single source via NSY_DB::connect() — powerful, DRY
        static::$connection = NSY_DB::connect($conn_name);
        if (!static::$connection) {
            $var_msg = "Database connection failed for '" . htmlspecialchars($conn_name, ENT_QUOTES, 'UTF-8') . "'";
            NSY_Desk::staticErrorHandler($var_msg);
        }
        return new static;
    }

    /**
     * Prepare connection for external class access
     *
     * @param string $conn_name
     * @return object
     */
    public static function getConnection(string $conn_name = 'primary')
    {
        if (!static::$connection) {
            static::connect($conn_name);
        }
        return static::$connection;
    }

    /**
     * Function as a query declaration
     *
     * @param  string $query
     * @return void
     */
    protected static function query(string $query = '')
    {
        if (is_filled($query)) {
            static::$query = $query;
        } else {
            $var_msg = "The value of query in the <mark>query(<strong>value</strong>)</mark> is empty or undefined";
            NSY_Desk::staticErrorHandler($var_msg);
        }

        return new static;
    }

    /**
     * Function as a variable container
     *
     * @param  array $variables
     * @return void
     */
    protected function vars(array $variables = array())
    {
        if (is_array($variables) || is_object($variables)) {
            static::$variables = $variables;
        } else {
            $var_msg = "The variable in the <mark>vars(<strong>variables</strong>)</mark> is improper or not an array";
            NSY_Desk::staticErrorHandler($var_msg);
        }

        return new static;
    }

    /**
     * Function as a fetch style declaration
     *
     * @param  int|string|null $fetch_style Fetch mode (e.g., \PDO::FETCH_ASSOC) or null for default
     * @return static
     */
    protected function style($fetch_style = null)
    {
        if (not_filled($fetch_style)) {
            // Default to PDO::FETCH_BOTH when not provided
            static::$fetch_style = \PDO::FETCH_BOTH;
        } else {
            static::$fetch_style = $fetch_style;
        }

        return new static;
    }

    /**
     * Start method for variables sequence (bind)
     *
     * @param  string $bind
     * @return void
     */
    protected function bind(string $bind = '')
    {
        if (is_filled($bind)) {
            static::$bind = $bind;
        } else {
            static::$bind = '';
        }

        return new static;
    }

    /**
     * Helper for PDO FetchAll
     *
     * @return array
     */
    protected function fetchAll()
    {
        if (not_filled(static::$connection)) {
            NSY_Desk::staticErrorHandler('No Connection, please check your connection again.', 500);
        }

        $stmt = static::$connection->prepare(static::$query);
        $executed = self::bindAndExecute($stmt);

        if ($executed || $stmt->errorCode() == 0) {
            $fetchStyle = static::$fetch_style ?? \PDO::FETCH_BOTH;

            return $stmt->fetchAll($fetchStyle);
        }

        $var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
        NSY_Desk::staticErrorHandler($var_msg);
    }

    /**
     * Helper for PDO Fetch
     *
     * @return mixed
     */
    protected function fetch()
    {
        if (not_filled(static::$connection)) {
            NSY_Desk::staticErrorHandler('No Connection, please check your connection again.', 500);
        }

        $stmt = static::$connection->prepare(static::$query);
        $executed = self::bindAndExecute($stmt);

        if ($executed || $stmt->errorCode() == 0) {
            $fetchStyle = static::$fetch_style ?? \PDO::FETCH_BOTH;

            return $stmt->fetch($fetchStyle);
        }

        $var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
        NSY_Desk::staticErrorHandler($var_msg);
    }

    /**
     * Helper for PDO FetchColumn
     *
     * @param  int $column
     * @return mixed
     */
    protected function fetchColumn(int $column = 0)
    {
        if (not_filled(static::$connection)) {
            NSY_Desk::staticErrorHandler('No Connection, please check your connection again.', 500);
        }

        $stmt = static::$connection->prepare(static::$query);
        $executed = self::bindAndExecute($stmt);

        if ($executed || $stmt->errorCode() == 0) {
            return $stmt->fetchColumn($column);
        }

        $var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
        NSY_Desk::staticErrorHandler($var_msg);
    }

    /**
     * Helper for PDO RowCount
     *
     * @return int
     */
    protected function rowCount()
    {
        if (not_filled(static::$connection)) {
            NSY_Desk::staticErrorHandler('No Connection, please check your connection again.', 500);
        }

        $stmt = static::$connection->prepare(static::$query);
        $executed = self::bindAndExecute($stmt);

        if ($executed || $stmt->errorCode() == 0) {
            return $stmt->rowCount();
        }

        $var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
        NSY_Desk::staticErrorHandler($var_msg);
    }

    /**
     * Helper for PDO Execute
     *
     * @return bool
     */
    protected function exec()
    {
        self::beginQueryLog();

        if (config_app('csrf_token') === 'true') {
            try {
                // CSRF validation using consolidated SecurityMiddleware implementation
                \System\Middlewares\SecurityMiddleware::validateAdvancedCSRF('csrf_token', $_POST, true, 60 * 10, false, false);
            } catch (\Exception $e) {
                // CSRF attack detected
                NSY_Desk::staticErrorHandler('CSRF: ' . $e->getMessage(), 403);
            }
        } elseif (config_app('csrf_token') !== 'false') {
            NSY_Desk::staticErrorHandler('The CSRF token protection is not set correctly. Check System/Config/App.php.', 500);
        }

        if (not_filled(static::$connection)) {
            NSY_Desk::staticErrorHandler('No Connection, please check your connection again.', 500);
        }

        $stmt = null;
        $executed = false;

        self::withTransaction(function () use (&$stmt, &$executed) {
            $stmt = static::$connection->prepare(static::$query);
            $executed = self::bindAndExecute($stmt);
        });

        if ($executed || ($stmt instanceof \PDOStatement && $stmt->errorCode() == 0)) {
            self::endQueryLog();
            return true;
        }

        if (not_filled(static::$variables)) {
            $var_msg = "Syntax error or access violation! \nNo parameter were bound for query, \nPlease check your query again!";
        } else {
            $var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
        }
        NSY_Desk::staticErrorHandler($var_msg);

        $stmt = null;
        static::$connection = null;
    }

    /**
     * Helper for PDO Multi Insert
     *
     * @return bool
     */
    protected function multiInsert()
    {
        self::beginQueryLog();

        if (config_app('csrf_token') === 'true') {
            try {
                // CSRF validation using consolidated SecurityMiddleware implementation
                \System\Middlewares\SecurityMiddleware::validateAdvancedCSRF('csrf_token', $_POST, true, 60 * 10, false, false);
            } catch (\Exception $e) {
                // CSRF attack detected
                NSY_Desk::staticErrorHandler('CSRF: ' . $e->getMessage(), 403);
            }
        } elseif (config_app('csrf_token') !== 'false') {
            NSY_Desk::staticErrorHandler('The CSRF token protection is not set correctly. Check System/Config/App.php.', 500);
        }

        if (not_filled(static::$connection)) {
            NSY_Desk::staticErrorHandler('No Connection, please check your connection again.', 500);
        }

        // Guard BEFORE touching $variables[0] (the old code read it first).
        if (not_filled(static::$variables)) {
            $var_msg = "Syntax error or access violation! \nNo parameter were bound for query, \nPlease check your query again!";
            NSY_Desk::staticErrorHandler($var_msg);
        }

        $rows = count(static::$variables);
        $cols = count((array) static::$variables[0]);
        $rowString = '(' . rtrim(str_repeat('?,', $cols), ',') . '),';
        $valString = rtrim(str_repeat($rowString, $rows), ',');
        $sql = static::$query . ' VALUES ' . $valString;

        $bindArray = [];
        array_walk_recursive(static::$variables, function ($item) use (&$bindArray) {
            $bindArray[] = $item;
        });

        $stmt = null;
        $executed = false;

        self::withTransaction(function () use ($sql, $bindArray, &$stmt, &$executed) {
            $stmt = static::$connection->prepare($sql);
            $executed = $stmt->execute($bindArray);
        });

        if ($executed || ($stmt instanceof \PDOStatement && $stmt->errorCode() == 0)) {
            self::endQueryLog();
            return true;
        }

        $var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
        NSY_Desk::staticErrorHandler($var_msg);

        $stmt = null;
        static::$connection = null;
    }

    /**
     * Helper for PDO setAttribute
     *
     * @param mixed $param
     * @param mixed $value
     * @return static
     */
    protected function pdoSetAttr(mixed $param = '', mixed $value = '')
    {
        static::$connection->setAttribute($param, $value);
        return new static;
    }

    /**
     * Helper for PDO getAttribute
     *
     * @param mixed $param
     * @return static
     */
    protected function pdoGetAttr(mixed $param = '')
    {
        static::$connection->getAttribute($param);
        return new static;
    }

    /**
     * Helper for PDO Begin Transaction
     *
     * @return static
     */
    protected function beginTrans()
    {
        static::$connection->beginTransaction();
        return new static;
    }
    /**
     * Helper for PDO Commit Transaction
     *
     * @return static
     */
    protected function commitTrans()
    {
        static::$connection->commit();
        return new static;
    }
    /**
     * Helper for PDO Rollback Transaction
     *
     * @return static
     */
    protected function rollbackTrans()
    {
        static::$connection->rollback();
        return new static;
    }

    /* =====================================================================
     * Public transaction API
     *
     * Wraps the shared PDO connection so application code can group writes.
     * All of these are additive: they never change how query()/exec() behave.
     * ===================================================================== */

    /**
     * Start a manual transaction on the shared connection.
     * Pair it with commit() / rollBack(). Prefer transaction() for the
     * common case so a thrown error always rolls back.
     *
     * @param  string $conn_name
     * @return bool
     */
    public static function beginTransaction(string $conn_name = 'primary'): bool
    {
        return static::getConnection($conn_name)->beginTransaction();
    }

    /**
     * Commit the active transaction.
     *
     * @return bool
     */
    public static function commit(): bool
    {
        return static::getConnection()->commit();
    }

    /**
     * Roll back the active transaction.
     *
     * @return bool
     */
    public static function rollBack(): bool
    {
        return static::getConnection()->rollBack();
    }

    /**
     * Is a transaction currently active on the shared connection?
     *
     * @return bool
     */
    public static function inTransaction(): bool
    {
        return static::getConnection()->inTransaction();
    }

    /**
     * Run a callback inside a database transaction.
     *
     * Commits when the callback returns; rolls back and re-throws when it
     * throws. The callback receives the shared PDO connection, so qb()/DB
     * calls made inside it join the same transaction.
     *
     * Example:
     *   DB::transaction(function () {
     *       qb('users')->insert([...]);
     *       qb('logs')->insert([...]);
     *   });
     *
     * Note: the legacy query path (DB::query()->exec()) already wraps each
     * write when config `transaction` is 'on'. Do not nest the two.
     *
     * @param  callable $callback
     * @param  string   $conn_name
     * @return mixed  The callback's return value
     */
    public static function transaction(callable $callback, string $conn_name = 'primary'): mixed
    {
        $pdo = static::getConnection($conn_name);
        $pdo->beginTransaction();

        try {
            $result = $callback($pdo);
            $pdo->commit();

            return $result;
        } catch (\Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }
}
