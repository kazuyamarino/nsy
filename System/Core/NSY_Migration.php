<?php

namespace System\Core;

/**
 * This is the core of NSY Migration
 * Attention, don't try to change the structure of the code, delete, or change.
 * Because there is some code connected to the NSY system. So, be careful.
 */
class NSY_Migration
{

	// Declare properties for Helper
	static $connection;
	static $primary;

	/**
	 * Default Connection
	 *
	 * @param string $conn_name
	 * @return mixed
	 */
	public static function connect($conn_name = 'primary')
	{
		switch (config_db($conn_name, 'DB_CONNECTION')) {
			case 'mysql':
				self::$connection = NSY_DB::connect_mysql($conn_name);
				return new self;
			case 'dblib':
				self::$connection = NSY_DB::connect_dblib($conn_name);
				return new self;
			case 'sqlsrv':
				self::$connection = NSY_DB::connect_sqlsrv($conn_name);
				return new self;
			case 'pgsql':
				self::$connection = NSY_DB::connect_pgsql($conn_name);
				return new self;
			default:
				$var_msg = "Default database connection not found or undefined, please configure it in <strong>.env</strong> file <strong><i>DB_CONNECTION</i></strong>";
				NSY_Desk::static_error_handler($var_msg);
				exit();
		}
	}

	/**
	 * Function for create database
	 *
	 * @param string $db
	 */
	public function create_db($db = '')
	{
		if (is_filled($db)) {
			$query = "CREATE DATABASE $db;";
			echo '<pre>' . $query . '</pre>';

			// Check if there's connection defined on the models
			if (not_filled(self::$connection)) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				// execute it
				$stmt = self::$connection->prepare($query);
				$executed = $stmt->execute();

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					return $executed;
				} else {
					if (config_app('transaction') === 'on') {
						self::$connection->rollback();

						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
					} elseif (config_app('transaction') === 'off') {
						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
					} else {
						echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
					}
				}
			}
		} else {
			$var_msg = "Database name in the <mark>create_db(<strong>value</strong>)</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		self::$connection = null;
		exit();
	}

	/**
	 * Function for delete database
	 *
	 * @param string $db
	 */
	public function drop_db($db = '')
	{
		if (is_filled($db)) {
			$query = "DROP DATABASE $db;";
			echo '<pre>' . $query . '</pre>';

			// Check if there's connection defined on the models
			if (not_filled(self::$connection)) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				// execute it
				$stmt = self::$connection->prepare($query);
				$executed = $stmt->execute();

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					return $executed;
				} else {
					if (config_app('transaction') === 'on') {
						self::$connection->rollback();

						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
					} elseif (config_app('transaction') === 'off') {
						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
					} else {
						echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
					}
				}
			}
		} else {
			$var_msg = "Database name in the <mark>drop_db(<strong>value</strong>)</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		self::$connection = null;
		exit();
	}

	/**
	 * Function for rename table (mysql/mariadb)
	 *
	 * @param string $old_table
	 * @param string $new_table
	 */
	public function rename_table($old_table = '', $new_table = '')
	{
		if (is_filled($old_table) || is_filled($new_table)) {
			$query = "RENAME TABLE $old_table TO $new_table;";
			echo '<pre>' . $query . '</pre>';

			// Check if there's connection defined on the models
			if (not_filled(self::$connection)) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				// execute it
				$stmt = self::$connection->prepare($query);
				$executed = $stmt->execute();

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					return $executed;
				} else {
					if (config_app('transaction') === 'on') {
						self::$connection->rollback();

						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
					} elseif (config_app('transaction') === 'off') {
						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
					} else {
						echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
					}
				}
			}
		} else {
			$var_msg = "Table name in the <mark>rename_table(<strong>old_table</strong>, <strong>new_table</strong>)</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		self::$connection = null;
		exit();
	}

	/**
	 * Function for alter rename table (postgre)
	 *
	 * @param string $old_table
	 * @param string $new_table
	 */
	public function alter_rename_table($old_table = '', $new_table = '')
	{
		if (is_filled($old_table) || is_filled($new_table)) {
			$query = "ALTER TABLE $old_table RENAME TO $new_table;";
			echo '<pre>' . $query . '</pre>';

			// Check if there's connection defined on the models
			if (not_filled(self::$connection)) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				// execute it
				$stmt = self::$connection->prepare($query);
				$executed = $stmt->execute();

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					return $executed;
				} else {
					if (config_app('transaction') === 'on') {
						self::$connection->rollback();

						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
					} elseif (config_app('transaction') === 'off') {
						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
					} else {
						echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
					}
				}
			}
		} else {
			$var_msg = "Table name in the <mark>alter_rename_table(<strong>old_table</strong>, <strong>new_table</strong>)</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		self::$connection = null;
		exit();
	}

	/**
	 * Function for sp rename table (mssql)
	 *
	 * @param string $old_table
	 * @param string $new_table
	 */
	public function sp_rename_table($old_table = '', $new_table = '')
	{
		if (is_filled($old_table) || is_filled($new_table)) {
			$query = "sp_rename '$old_table', '$new_table';";
			echo '<pre>' . $query . '</pre>';

			// Check if there's connection defined on the models
			if (not_filled(self::$connection)) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				// execute it
				$stmt = self::$connection->prepare($query);
				$executed = $stmt->execute();

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					return $executed;
				} else {
					if (config_app('transaction') === 'on') {
						self::$connection->rollback();

						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
					} elseif (config_app('transaction') === 'off') {
						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
					} else {
						echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
					}
				}
			}
		} else {
			$var_msg = "Table name in the <mark>sp_rename_table(<strong>old_table</strong>, <strong>new_table</strong>)</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		self::$connection = null;
		exit();
	}

	/**
	 * Function for delete table
	 *
	 * @param string $table
	 */
	public function drop_table($table = '')
	{
		if (is_filled($table)) {
			$query = "DROP TABLE $table;";
			echo '<pre>' . $query . '</pre>';

			// Check if there's connection defined on the models
			if (not_filled(self::$connection)) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				// execute it
				$stmt = self::$connection->prepare($query);
				$executed = $stmt->execute();

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					return $executed;
				} else {
					if (config_app('transaction') === 'on') {
						self::$connection->rollback();

						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
					} elseif (config_app('transaction') === 'off') {
						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
					} else {
						echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
					}
				}
			}
		} else {
			$var_msg = "Table name in the <mark>drop_table(<strong>value</strong>)</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		self::$connection = null;
		exit();
	}

	/**
	 * Function for delete table if exist
	 *
	 * @param string $table
	 */
	public function drop_exist_table($table = '')
	{
		if (is_filled($table)) {
			$query = "DROP TABLE IF EXISTS $table;";
			echo '<pre>' . $query . '</pre>';

			// Check if there's connection defined on the models
			if (not_filled(self::$connection)) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				// execute it
				$stmt = self::$connection->prepare($query);
				$executed = $stmt->execute();

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					return $executed;
				} else {
					if (config_app('transaction') === 'on') {
						self::$connection->rollback();

						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
					} elseif (config_app('transaction') === 'off') {
						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
					} else {
						echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
					}
				}
			}
		} else {
			$var_msg = "Table name in the <mark>drop_exist_table(<strong>value</strong>)</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		self::$connection = null;
		exit();
	}

	/**
	 * Function for create indexes
	 *
	 * Define Indexes Key
	 *
	 * @param  string $table
	 * @param  string $type
	 * @param  array $cols
	 * @return string
	 */
	public function index($table, $type, $cols = array())
	{
		if (is_filled($table)) {
			if (is_array($cols) || is_object($cols)) {
				$res = array();
				foreach ($cols as $key => $col) {
					$res[] = $col;
				}
				$im_cols = implode(', ', $res);

				$query = 'CREATE INDEX MULTI_' . generate_num(1, 5, 6) . '_IDX USING ' . $type . ' ON ' . $table . ' ( ' . $im_cols . ' ) ';
			} else {
				$query = 'CREATE INDEX ' . substr($cols, 0, 5) . '_' . generate_num(1, 2, 3) . '_IDX USING ' . $type . ' ON ' . $table . ' ( ' . $cols . ' ) ';
			}
			echo '<pre>' . $query . '</pre>';

			// Check if there's connection defined on the models
			if (not_filled(self::$connection)) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				// execute it
				$stmt = self::$connection->prepare($query);
				$executed = $stmt->execute();

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					return $executed;
				} else {
					if (config_app('transaction') === 'on') {
						self::$connection->rollback();

						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
					} elseif (config_app('transaction') === 'off') {
						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
					} else {
						echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
					}
				}
			}
		} else {
			$var_msg = "Table name in the <mark>index(<strong>value</strong>)</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		self::$connection = null;
		exit();
	}

	/**
	 * Function for create indexes (pgsql)
	 *
	 * Define Indexes Key
	 *
	 * @param  string $table
	 * @param  string $type
	 * @param  array $cols
	 * @return string
	 */
	public function index_pg($table, $type, $cols = array())
	{
		if (is_filled($table)) {
			if (is_array($cols) || is_object($cols)) {
				$res = array();
				foreach ($cols as $key => $col) {
					$res[] = $col;
				}
				$im_cols = implode(', ', $res);

				$query = 'CREATE INDEX MULTI_' . generate_num(1, 5, 6) . '_IDX ON ' . $table . ' USING ' . $type . ' ( ' . $im_cols . ' ) ';
			} else {
				$query = 'CREATE INDEX ' . substr($cols, 0, 5) . '_' . generate_num(1, 5, 6) . '_IDX ON ' . $table . ' USING ' . $type . ' ( ' . $cols . ' ) ';
			}
			echo '<pre>' . $query . '</pre>';

			// Check if there's connection defined on the models
			if (not_filled(self::$connection)) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				// execute it
				$stmt = self::$connection->prepare($query);
				$executed = $stmt->execute();

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					return $executed;
				} else {
					if (config_app('transaction') === 'on') {
						self::$connection->rollback();

						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
					} elseif (config_app('transaction') === 'off') {
						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
					} else {
						echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
					}
				}
			}
		} else {
			$var_msg = "Table name in the <mark>index_pg(<strong>value</strong>)</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		self::$connection = null;
		exit();
	}

	/**
	 * Define Primary Key
	 *
	 * @param  array $cols
	 * @return string
	 */
	public static function primary($cols = array())
	{
		if (is_array($cols) || is_object($cols)) {
			$res = array();
			foreach ($cols as $key => $col) {
				$res[] = $col;
			}
			$im_cols = implode(', ', $res);
			$con_cols = implode('_', $res);

			return self::$primary = 'CONSTRAINT ' . $con_cols . '_' . generate_num(1, 5, 6) . '_PK PRIMARY KEY (' . $im_cols . ')';
		} else {
			return self::$primary = 'CONSTRAINT ' . $cols . '_' . generate_num(1, 5, 6) . '_PK PRIMARY KEY (' . $cols . ')';
		}
	}

	/**
	 * Define Unique Key
	 *
	 * @param  array $cols
	 * @return string
	 */
	public static function unique($cols = array())
	{
		if (is_array($cols) || is_object($cols)) {
			$res = array();
			foreach ($cols as $key => $col) {
				$res[] = $col;
			}
			$im_cols = implode(', ', $res);
			$con_cols = implode('_', $res);

			return self::$primary = 'CONSTRAINT ' . $con_cols . '_' . generate_num(1, 5, 6) . '_UN UNIQUE (' . $im_cols . ')';
		} else {
			return self::$primary = 'CONSTRAINT ' . $cols . '_' . generate_num(1, 5, 6) . '_UN UNIQUE (' . $cols . ')';
		}
	}

	/**
	 * Columns with created, update, additional date
	 *
	 * @return array
	 */
	public static function timestamps()
	{
		$arr_date_cols = [
			'create_date' => 'DATETIME DEFAULT CURRENT_TIMESTAMP',
			'update_date' => 'DATETIME DEFAULT CURRENT_TIMESTAMP',
			'additional_date' => 'DATETIME DEFAULT CURRENT_TIMESTAMP'
		];

		return $arr_date_cols;
	}

	/**
	 * Columns with user defined variables
	 *
	 * @param array $cols
	 * @param array $other_cols
	 * @param array $timestamps_cols
	 */
	public static function cols($cols = array(), $other_cols = array())
	{
		$timestamps_cols = self::timestamps();

		if (is_array($cols) || is_object($cols)) {
			if (is_filled($other_cols) || is_filled($timestamps_cols)) {
				$res_merge_arr = array_merge($cols, $other_cols, $timestamps_cols);
				return $res_merge_arr;
			} else {
				return $cols;
			}
		} else {
			$var_msg = "The variable in the <mark>cols(<strong>value</strong>)</mark> is improper or not an array";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}
	}

	/**
	 * Function for create table
	 *
	 * @param string  $table
	 * @param \Closure $closure
	 */
	public function create_table(string $table = null, \Closure $closure)
	{
		if (is_filled($table)) {
			$closure_res = array();
			foreach ($closure() as $key => $closure_dt) {
				if (strpos($closure_dt, 'PRIMARY') || strpos($closure_dt, 'UNIQUE')) {
					$closure_res[] = $closure_dt;
				} else {
					$closure_res[] = $key . ' ' . $closure_dt;
				}
			}
			$closure_imp = implode(",\n", $closure_res) . "\n";

			$query = "CREATE TABLE " . $table . " ( " . $closure_imp . " );\n";
			echo '<pre>' . $query . '</pre>';

			// Check if there's connection defined on the models
			if (not_filled(self::$connection)) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				// execute it
				$stmt = self::$connection->prepare($query);
				$executed = $stmt->execute();

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					return $executed;
				} else {
					if (config_app('transaction') === 'on') {
						self::$connection->rollback();

						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
					} elseif (config_app('transaction') === 'off') {
						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
					} else {
						echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
					}
				}
			}
		} else {
			$var_msg = "Table name in the <mark>create_table(<strong>table</strong>, value)</mark> and \nColumns in the <mark>create_table(table, <strong>value</strong>)</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		self::$connection = null;
		exit();
	}

	/**
	 * Function for add column (mssql)
	 *
	 * @param string  $table
	 * @param \Closure $closure
	 */
	public function add(string $table = null, \Closure $closure)
	{
		if (is_filled($table)) {
			$closure_res = array();
			foreach ($closure() as $key => $closure_dt) {
				$closure_res[] = 'ALTER TABLE ' . $table . ' ADD ' . $key . ' ' . $closure_dt;
			}
			$closure_imp = implode(";\n", $closure_res) . ";\n";

			$query = $closure_imp;
			echo '<pre>' . $query . '</pre>';

			// Check if there's connection defined on the models
			if (not_filled(self::$connection)) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				// execute it
				$stmt = self::$connection->prepare($query);
				$executed = $stmt->execute();

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					return $executed;
				} else {
					if (config_app('transaction') === 'on') {
						self::$connection->rollback();

						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
					} elseif (config_app('transaction') === 'off') {
						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
					} else {
						echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
					}
				}
			}
		} else {
			$var_msg = "Table name in the <mark>add(<strong>table</strong>, value)</mark> and \nColumns in the <mark>add(table, <strong>value</strong>)</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		self::$connection = null;
		exit();
	}

	/**
	 * Function for add column (mysql/mariadb/postgre)
	 *
	 * @param string  $table
	 * @param \Closure $closure
	 */
	public function add_cols(string $table = null, \Closure $closure)
	{
		if (is_filled($table)) {
			$closure_res = array();
			foreach ($closure() as $key => $closure_dt) {
				$closure_res[] = 'ALTER TABLE ' . $table . ' ADD COLUMN ' . $key . ' ' . $closure_dt;
			}
			$closure_imp = implode(";\n", $closure_res) . ";\n";

			$query = $closure_imp;
			echo '<pre>' . $query . '</pre>';

			// Check if there's connection defined on the models
			if (not_filled(self::$connection)) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				// execute it
				$stmt = self::$connection->prepare($query);
				$executed = $stmt->execute();

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					return $executed;
				} else {
					if (config_app('transaction') === 'on') {
						self::$connection->rollback();

						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
					} elseif (config_app('transaction') === 'off') {
						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
					} else {
						echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
					}
				}
			}
		} else {
			$var_msg = "Table name in the <mark>add_cols(<strong>table</strong>, value)</mark> and \nColumns in the <mark>add_cols(table, <strong>value</strong>)</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		self::$connection = null;
		exit();
	}

	/**
	 * Function for drop column (mysql/mariadb/postgre/mssql)
	 *
	 * @param string  $table
	 * @param \Closure $closure
	 */
	public function drop_cols(string $table = null, \Closure $closure)
	{
		if (is_filled($table)) {
			$closure_res = array();
			foreach ($closure() as $key => $closure_dt) {
				$closure_res[] = 'ALTER TABLE ' . $table . ' DROP COLUMN ' . $closure_dt;
			}
			$closure_imp = implode(";\n", $closure_res) . ";\n";

			$query = $closure_imp;
			echo '<pre>' . $query . '</pre>';

			// Check if there's connection defined on the models
			if (not_filled(self::$connection)) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				// execute it
				$stmt = self::$connection->prepare($query);
				$executed = $stmt->execute();

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					return $executed;
				} else {
					if (config_app('transaction') === 'on') {
						self::$connection->rollback();

						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
					} elseif (config_app('transaction') === 'off') {
						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
					} else {
						echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
					}
				}
			}
		} else {
			$var_msg = "Table name in the <mark>drop_cols(<strong>table</strong>, value)</mark> and \nColumns in the <mark>drop_cols(table, <strong>value</strong>)</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		self::$connection = null;
		exit();
	}

	/**
	 * Function for alter column (mssql/postgre)
	 *
	 * @param string  $table
	 * @param \Closure $closure
	 */
	public function alter_cols(string $table = null, \Closure $closure)
	{
		if (is_filled($table)) {
			$closure_res = array();
			foreach ($closure() as $key => $closure_dt) {
				if (strpos($closure_dt, 'PRIMARY') || strpos($closure_dt, 'UNIQUE')) {
					$closure_res[] = 'ALTER TABLE ' . $table . ' ADD ' . $closure_dt;
				} else {
					$closure_res[] = 'ALTER TABLE ' . $table . ' ALTER COLUMN ' . $key . ' ' . $closure_dt;
				}
			}
			$closure_imp = implode(";\n", $closure_res) . ";\n";

			$query = $closure_imp;
			echo '<pre>' . $query . '</pre>';

			// Check if there's connection defined on the models
			if (not_filled(self::$connection)) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				// execute it
				$stmt = self::$connection->prepare($query);
				$executed = $stmt->execute();

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					return $executed;
				} else {
					if (config_app('transaction') === 'on') {
						self::$connection->rollback();

						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
					} elseif (config_app('transaction') === 'off') {
						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
					} else {
						echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
					}
				}
			}
		} else {
			$var_msg = "Table name in the <mark>alter_cols(<strong>table</strong>, value)</mark> and \nColumns in the <mark>alter_cols(table, <strong>value</strong>)</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		self::$connection = null;
		exit();
	}

	/**
	 * Function for modify column (mysql/mariadb)
	 *
	 * @param string  $table
	 * @param \Closure $closure
	 */
	public function modify_cols(string $table = null, \Closure $closure)
	{
		if (is_filled($table)) {
			$closure_res = array();
			foreach ($closure() as $key => $closure_dt) {
				if (strpos($closure_dt, 'PRIMARY') || strpos($closure_dt, 'UNIQUE')) {
					$closure_res[] = 'ALTER TABLE ' . $table . ' ADD ' . $closure_dt;
				} else {
					$closure_res[] = 'ALTER TABLE ' . $table . ' MODIFY COLUMN ' . $key . ' ' . $closure_dt;
				}
			}
			$closure_imp = implode(";\n", $closure_res) . ";\n";

			$query = $closure_imp;
			echo '<pre>' . $query . '</pre>';

			// Check if there's connection defined on the models
			if (not_filled(self::$connection)) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				// execute it
				$stmt = self::$connection->prepare($query);
				$executed = $stmt->execute();

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					return $executed;
				} else {
					if (config_app('transaction') === 'on') {
						self::$connection->rollback();

						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
					} elseif (config_app('transaction') === 'off') {
						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
					} else {
						echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
					}
				}
			}
		} else {
			$var_msg = "Table name in the <mark>modify_cols(<strong>table</strong>, value)</mark> and \nColumns in the <mark>modify_cols(table, <strong>value</strong>)</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		self::$connection = null;
		exit();
	}

	/**
	 * Function for rename column (postgre)
	 *
	 * @param string  $table
	 * @param \Closure $closure
	 */
	public function rename_cols(string $table = null, \Closure $closure)
	{
		if (is_filled($table)) {
			$closure_res = array();
			foreach ($closure() as $key => $closure_dt) {
				$closure_res[] = 'ALTER TABLE ' . $table . ' RENAME COLUMN ' . $key . ' TO ' . $closure_dt;
			}
			$closure_imp = implode(";\n", $closure_res) . ";\n";

			$query = $closure_imp;
			echo '<pre>' . $query . '</pre>';

			// Check if there's connection defined on the models
			if (not_filled(self::$connection)) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				// execute it
				$stmt = self::$connection->prepare($query);
				$executed = $stmt->execute();

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					return $executed;
				} else {
					if (config_app('transaction') === 'on') {
						self::$connection->rollback();

						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
					} elseif (config_app('transaction') === 'off') {
						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
					} else {
						echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
					}
				}
			}
		} else {
			$var_msg = "Table name in the <mark>rename_cols(<strong>table</strong>, value)</mark> and \nColumns in the <mark>rename_cols(table, <strong>value</strong>)</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		self::$connection = null;
		exit();
	}

	/**
	 * Function for change column (mysql/mariadb)
	 *
	 * @param string  $table
	 * @param \Closure $closure
	 */
	public function change_cols(string $table = null, \Closure $closure)
	{
		if (is_filled($table)) {
			$closure_res = array();
			foreach ($closure() as $key => $closure_dt) {
				$closure_res[] = 'ALTER TABLE ' . $table . ' CHANGE ' . $key . ' ' . $closure_dt;
			}
			$closure_imp = implode(";\n", $closure_res) . ";\n";

			$query = $closure_imp;
			echo '<pre>' . $query . '</pre>';

			// Check if there's connection defined on the models
			if (not_filled(self::$connection)) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				// execute it
				$stmt = self::$connection->prepare($query);
				$executed = $stmt->execute();

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					return $executed;
				} else {
					if (config_app('transaction') === 'on') {
						self::$connection->rollback();

						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
					} elseif (config_app('transaction') === 'off') {
						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
					} else {
						echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
					}
				}
			}
		} else {
			$var_msg = "Table name in the <mark>change_cols(<strong>table</strong>, value)</mark> and \nColumns in the <mark>change_cols(table, <strong>value</strong>)</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		self::$connection = null;
		exit();
	}

	/**
	 * Function for sp rename column (mssql)
	 *
	 * @param string  $table
	 * @param \Closure $closure
	 */
	public function sp_rename_cols(string $table = null, \Closure $closure)
	{
		if (is_filled($table)) {
			$closure_res = array();
			foreach ($closure() as $key => $closure_dt) {
				$closure_res[] = "exec sp_rename '" . $table . "." . $key . "', '" . $closure_dt . "', 'COLUMN'";
			}
			$closure_imp = implode(";\n", $closure_res) . ";\n";

			$query = $closure_imp;
			echo '<pre>' . $query . '</pre>';

			// Check if there's connection defined on the models
			if (not_filled(self::$connection)) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				// execute it
				$stmt = self::$connection->prepare($query);
				$executed = $stmt->execute();

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					return $executed;
				} else {
					if (config_app('transaction') === 'on') {
						self::$connection->rollback();

						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
					} elseif (config_app('transaction') === 'off') {
						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
					} else {
						echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
					}
				}
			}
		} else {
			$var_msg = "Table name in the <mark>sp_rename_cols(<strong>table</strong>, value)</mark> and \nColumns in the <mark>sp_rename_cols(table, <strong>value</strong>)</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		self::$connection = null;
		exit();
	}

	/**
	 * Define bit datatype
	 *
	 */
	public static function bit()
	{
		return "bit NOT NULL";
	}

	/**
	 * Define tinyint datatype
	 *
	 * @param int $length
	 */
	public static function tinyint(int $length = 4)
	{
		return "tinyint($length) NOT NULL";
	}

	/**
	 * Define smallint datatype
	 *
	 * @param int $length
	 */
	public static function smallint(int $length = 5)
	{
		return "smallint($length) NOT NULL";
	}

	/**
	 * Define mediumint datatype
	 *
	 * @param int $length
	 */
	public static function mediumint(int $length = 9)
	{
		return "mediumint($length) NOT NULL";
	}

	/**
	 * Define int datatype
	 *
	 * @param int $length
	 */
	public static function int(int $length = 11)
	{
		return "int($length) NOT NULL";
	}

	/**
	 * Define integer datatype
	 *
	 * @param int $length
	 */
	public static function integer(int $length = 11)
	{
		return "integer($length) NOT NULL";
	}

	/**
	 * Define bigint datatype
	 *
	 * @param int $length
	 */
	public static function bigint(int $length = 20)
	{
		return "bigint($length) NOT NULL";
	}

	/**
	 * Define decimal datatype
	 *
	 * @param int $length
	 * @param int $decimal
	 */
	public static function decimal(int $length = 10, int $decimal = 0)
	{
		return "decimal($length, $decimal) NOT NULL";
	}

	/**
	 * Define dec datatype
	 *
	 * @param int $length
	 * @param int $decimal
	 */
	public static function dec(int $length = 10, int $decimal = 0)
	{
		return "dec($length, $decimal) NOT NULL";
	}

	/**
	 * Define numeric datatype
	 *
	 * @param int $length
	 * @param int $decimal
	 */
	public static function numeric(int $length = 10, int $decimal = 0)
	{
		return "numeric($length, $decimal) NOT NULL";
	}

	/**
	 * Define fixed datatype
	 *
	 * @param int $length
	 * @param int $decimal
	 */
	public static function fixed(int $length = 10, int $decimal = 0)
	{
		return "fixed($length, $decimal) NOT NULL";
	}

	/**
	 * Define float datatype
	 *
	 * @param int $length
	 * @param int $decimal
	 */
	public static function float(int $length = 10, int $decimal = 0)
	{
		return "float($length, $decimal) NOT NULL";
	}

	/**
	 * Define float precision datatype
	 *
	 * @param int $precision
	 */
	public static function float_precision(int $precision = 0)
	{
		return "float($precision) NOT NULL";
	}

	/**
	 * Define double datatype
	 *
	 * @param int $length
	 * @param int $decimal
	 * 
	 */
	public static function double(int $length = 10, int $decimal = 0)
	{
		return "double($length, $decimal) NOT NULL";
	}

	/**
	 * Define double precision datatype
	 *
	 * @param int $length
	 * @param int $decimal
	 */
	public static function double_precision(int $length = 10, int $decimal = 0)
	{
		return "double precision($length, $decimal) NOT NULL";
	}

	/**
	 * Define real datatype
	 *
	 * @param int $length
	 * @param int $decimal
	 */
	public static function real(int $length = 10, int $decimal = 0)
	{
		return "real($length, $decimal) NOT NULL";
	}

	/**
	 * Define bool datatype
	 *
	 */
	public static function bool()
	{
		return "bool NOT NULL";
	}

	/**
	 * Define boolean datatype
	 *
	 */
	public static function boolean()
	{
		return "boolean NOT NULL";
	}
}
