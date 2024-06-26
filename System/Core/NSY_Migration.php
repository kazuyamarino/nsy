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
	private static $connection;
	protected static $primary;
	protected static $datatype;
	protected $current_table;

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
	 * Function for create database (mysql/mariadb/postgresql)
	 *
	 * @param array $db
	 */
	public function create_database(array $arr_db = [])
	{
		if (is_filled($arr_db)) {
			// Check if there's connection defined on the models
			if (not_filled(self::$connection)) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				foreach ($arr_db as $db) {
					$query = "CREATE DATABASE $db;";
					echo '<pre>' . $query . '</pre>';

					// execute it
					$stmt = self::$connection->prepare($query);
					$executed = $stmt->execute();
				}

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					// Return $this to allow chaining
					return $this;
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
			$var_msg = "Database name in the <mark>create_database(<strong>value</strong>)</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		self::$connection = null;
		exit();
	}

	/**
	 * Function for delete database (mysql/mariadb)
	 *
	 * @param array $db
	 */
	public function drop_database(array $arr_db = [])
	{
		if (is_filled($arr_db)) {
			// Check if there's connection defined on the models
			if (not_filled(self::$connection)) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				foreach ($arr_db as $db) {
					$query = "DROP DATABASE $db;";
					echo '<pre>' . $query . '</pre>';

					// execute it
					$stmt = self::$connection->prepare($query);
					$executed = $stmt->execute();
				}

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					// Return $this to allow chaining
					return $this;
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
			$var_msg = "Database name in the <mark>drop_database(<strong>value</strong>)</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		self::$connection = null;
		exit();
	}

	/**
	 * Function for creating a table with user-defined columns (mysql/mariadb)
	 *
	 * @param string $table
	 * @param array $columns
	 * @return $this
	 */
	public function create_table(string $table = null, array $columns = [], $timestamps_mark = 'enabled')
	{
		$timestamps_cols = self::timestamps();
		$this->current_table = $table;

		if (is_filled($table) && !empty($columns)) {
			// Generate the columns part of the query
			if ($timestamps_mark == 'enabled') {
				$columns = array_merge($columns, $timestamps_cols);
			}

			$columns_str = implode(",\n", $columns) . "\n";
			$query = "CREATE TABLE {$table} ( {$columns_str} );\n";
			echo '<pre>' . $query . '</pre>';

			// Check if there's a valid database connection
			if (not_filled(self::$connection)) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				// Prepare and execute the query
				$stmt = self::$connection->prepare($query);
				$executed = $stmt->execute();

				// Handle errors if any
				if ($executed || $stmt->errorCode() == 0) {
					// Return $this to allow chaining
					return $this;
				} else {
					// Rollback transaction if enabled and handle error
					if (config_app('transaction') === 'on') {
						self::$connection->rollback();
					}
					$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
					NSY_Desk::static_error_handler($var_msg);
				}
			}
		} else {
			// Handle case where table name or columns are empty or undefined
			$var_msg = "Table name or columns are empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		// Close the statement and connection
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
					// Return $this to allow chaining
					return $this;
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
	 * Function for alter rename table (postgresql)
	 *
	 * @param string $old_table
	 * @param string $new_table
	 */
	public function rename_table_pg($old_table = '', $new_table = '')
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
					// Return $this to allow chaining
					return $this;
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
			$var_msg = "Table name in the <mark>rename_table_pg(<strong>old_table</strong>, <strong>new_table</strong>)</mark> is empty or undefined";
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
	public function rename_table_ms($old_table = '', $new_table = '')
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
					// Return $this to allow chaining
					return $this;
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
			$var_msg = "Table name in the <mark>rename_table_ms(<strong>old_table</strong>, <strong>new_table</strong>)</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		self::$connection = null;
		exit();
	}

	/**
	 * Function for delete table (mysql/mariadb)
	 *
	 * @param array $table
	 */
	public function drop_table(array $arr_table = [])
	{
		if (is_filled($arr_table)) {
			// Check if there's connection defined on the models
			if (not_filled(self::$connection)) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				foreach ($arr_table as $table) {
					$query = "DROP TABLE $table;";
					echo '<pre>' . $query . '</pre>';

					// execute it
					$stmt = self::$connection->prepare($query);
					$executed = $stmt->execute();
				}

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					// Return $this to allow chaining
					return $this;
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
	 * Function for delete table if exist (mysql/mariadb)
	 *
	 * @param array $table
	 */
	public function drop_exist_table(array $arr_table = [])
	{
		if (is_filled($arr_table)) {
			// Check if there's connection defined on the models
			if (not_filled(self::$connection)) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				foreach ($arr_table as $table) {
					$query = "DROP TABLE IF EXISTS $table;";
					echo '<pre>' . $query . '</pre>';

					// execute it
					$stmt = self::$connection->prepare($query);
					$executed = $stmt->execute();
				}

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					// Return $this to allow chaining
					return $this;
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
	 * Function for creating an index on a table (mysql/mariadb)
	 *
	 * @param string $table
	 * @param string $type
	 * @param array|string $cols
	 * @return bool
	 */
	public function index(string $type, $cols = [])
	{
		$table = $this->current_table;

		if (is_filled($table) && (is_array($cols) || is_string($cols))) {
			if (is_array($cols)) {
				$im_cols = implode(', ', $cols);
			} else {
				$im_cols = $cols;
			}

			$query = 'CREATE INDEX MULTI_' . generate_num(1, 5, 6) . '_IDX USING ' . $type . ' ON ' . $table . ' ( ' . $im_cols . ' ) ';
			echo '<pre>' . $query . '</pre>';

			// Check if there's a connection defined on the models
			if (not_filled(self::$connection)) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				// execute it
				$stmt = self::$connection->prepare($query);
				$executed = $stmt->execute();

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					return true;
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

		return false;
	}

	/**
	 * Function for create indexes (postgresql)
	 *
	 * Define Indexes Key
	 *
	 * @param  string $table
	 * @param  string $type
	 * @param  array $cols
	 * @return string
	 */
	public function index_pg(string $type, $cols = [])
	{
		$table = $this->current_table;

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
					return true;
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

		return false;
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

			return self::$primary = 'CONSTRAINT ' . generate_num(1, 5, 6) . '_PK PRIMARY KEY (' . $im_cols . ')';
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

			return self::$primary = 'CONSTRAINT ' . generate_num(1, 5, 6) . '_UN UNIQUE (' . $im_cols . ')';
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
			'create_date DATETIME DEFAULT CURRENT_TIMESTAMP',
			'update_date DATETIME DEFAULT CURRENT_TIMESTAMP',
			'delete_date DATETIME DEFAULT CURRENT_TIMESTAMP'
		];

		return $arr_date_cols;
	}

	// /**
	//  * Columns with user defined variables
	//  *
	//  * @param array $cols
	//  * @param array $timestamps_cols
	//  */
	// public static function cols($cols = array(), $timestamps_mark = 'enabled')
	// {
	// 	$timestamps_cols = self::timestamps();

	// 	if (is_array($cols) || is_object($cols)) {
	// 		if ($timestamps_mark == 'enabled') {
	// 			if (is_filled($timestamps_cols)) {
	// 				$merge_cols = array_merge($cols, $timestamps_cols);
	// 				return $merge_cols;
	// 			} else {
	// 				return $cols;
	// 			}
	// 		} elseif ($timestamps_mark == 'disabled') {
	// 			return $cols;
	// 		}
	// 	} else {
	// 		$var_msg = "The variable in the <mark>cols(<strong>value</strong>)</mark> is improper or not an array";
	// 		NSY_Desk::static_error_handler($var_msg);
	// 		exit();
	// 	}
	// }

	/**
	 * Function for add column (mssql)
	 *
	 * @param string  $table
	 * @param array $columns
	 */
	public function add_cols_ms(string $table = null, array $columns = [])
	{
		if (is_filled($table)) {
			// Check if there's connection defined on the models
			if (not_filled(self::$connection)) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				foreach ($columns as $closure_dt) {
					$query = 'ALTER TABLE ' . $table . ' ADD ' . ' ' . $closure_dt . ';';

					echo '<pre>' . $query . '</pre>';

					// execute it
					$stmt = self::$connection->prepare($query);
					$executed = $stmt->execute();
				}

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					// Return $this to allow chaining
					return $this;
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
			$var_msg = "Table name in the <mark>add_cols_ms(<strong>table</strong>, value)</mark> and \nColumns in the <mark>add_cols_ms(table, <strong>value</strong>)</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		self::$connection = null;
		exit();
	}

	/**
	 * Function for add column (mysql/mariadb/postgresql)
	 *
	 * @param string  $table
	 * @param array $columns
	 */
	public function add_cols(string $table = null, array $columns = [])
	{
		if (is_filled($table)) {
			// Check if there's connection defined on the models
			if (not_filled(self::$connection)) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				foreach ($columns as $closure_dt) {
					$query = 'ALTER TABLE ' . $table . ' ADD COLUMN ' . ' ' . $closure_dt . ';';
					echo '<pre>' . $query . '</pre>';

					// execute it
					$stmt = self::$connection->prepare($query);
					$executed = $stmt->execute();
				}

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					// Return $this to allow chaining
					return $this;
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
	 * Function for drop column (mysql/mariadb/postgresql/mssql)
	 *
	 * @param string  $table
	 * @param array $columns
	 */
	public function drop_cols(string $table = null, array $columns = [])
	{
		if (is_filled($table)) {
			// Check if there's connection defined on the models
			if (not_filled(self::$connection)) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				foreach ($columns as $closure_dt) {
					$query = 'ALTER TABLE ' . $table . ' DROP COLUMN ' . $closure_dt . ';';
					echo '<pre>' . $query . '</pre>';

					// execute it
					$stmt = self::$connection->prepare($query);
					$executed = $stmt->execute();
				}

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					// Return $this to allow chaining
					return $this;
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
	 * Function for alter column (mssql/postgresql)
	 *
	 * @param string  $table
	 * @param array $columns
	 */
	public function modify_cols_ext(string $table = null, array $columns = [])
	{
		if (is_filled($table)) {
			// Check if there's connection defined on the models
			if (not_filled(self::$connection)) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				foreach ($columns as $key => $closure_dt) {
					if (strpos($closure_dt, 'PRIMARY') || strpos($closure_dt, 'UNIQUE')) {
						$query = 'ALTER TABLE ' . $table . ' ADD ' . $closure_dt . ';';
					} else {
						$query = 'ALTER TABLE ' . $table . ' ALTER COLUMN ' . $key . ' ' . $closure_dt . ';';
					}

					echo '<pre>' . $query . '</pre>';

					// execute it
					$stmt = self::$connection->prepare($query);
					$executed = $stmt->execute();
				}

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					// Return $this to allow chaining
					return $this;
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
			$var_msg = "Table name in the <mark>modify_cols_ext(<strong>table</strong>, value)</mark> and \nColumns in the <mark>modify_cols_ext(table, <strong>value</strong>)</mark> is empty or undefined";
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
	 * @param array $columns
	 */
	public function modify_cols(string $table = null, array $columns = [])
	{
		if (is_filled($table)) {
			// Check if there's connection defined on the models
			if (not_filled(self::$connection)) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				foreach ($columns as $closure_dt) {
					if (strpos($closure_dt, 'PRIMARY') || strpos($closure_dt, 'UNIQUE')) {
						$query = 'ALTER TABLE ' . $table . ' ADD ' . $closure_dt . ';';
					} else {
						$query = 'ALTER TABLE ' . $table . ' MODIFY COLUMN ' . ' ' . $closure_dt . ';';
					}

					echo '<pre>' . $query . '</pre>';

					// execute it
					$stmt = self::$connection->prepare($query);
					$executed = $stmt->execute();
				}

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					// Return $this to allow chaining
					return $this;
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
	 * Function for rename column (mysql/mariadb/postgresql)
	 *
	 * @param string  $table
	 * @param array $columns
	 */
	public function rename_cols(string $table = null, array $columns = [])
	{
		if (is_filled($table)) {
			// Check if there's connection defined on the models
			if (not_filled(self::$connection)) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				foreach ($columns as $key => $closure_dt) {
					$query = 'ALTER TABLE ' . $table . ' RENAME COLUMN ' . $key . ' TO ' . $closure_dt . ';';
					echo '<pre>' . $query . '</pre>';

					// execute it
					$stmt = self::$connection->prepare($query);
					$executed = $stmt->execute();
				}

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					// Return $this to allow chaining
					return $this;
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
	 * Function for sp rename column (mssql)
	 *
	 * @param string  $table
	 * @param array $columns
	 */
	public function rename_cols_ms(string $table = null, array $columns = [])
	{
		if (is_filled($table)) {
			// Check if there's connection defined on the models
			if (not_filled(self::$connection)) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				foreach ($columns as $closure_dt) {
					$query = "exec sp_rename '" . $table . "." . "', '" . $closure_dt . "', 'COLUMN'";
					echo '<pre>' . $query . '</pre>';

					// execute it
					$stmt = self::$connection->prepare($query);
					$executed = $stmt->execute();
				}

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					// Return $this to allow chaining
					return $this;
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
			$var_msg = "Table name in the <mark>rename_cols_ms(<strong>table</strong>, value)</mark> and \nColumns in the <mark>rename_cols_ms(table, <strong>value</strong>)</mark> is empty or undefined";
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
	 * @param array $columns
	 */
	public function change_cols(string $table = null, array $columns = [])
	{
		if (is_filled($table)) {
			// Check if there's connection defined on the models
			if (not_filled(self::$connection)) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				foreach ($columns as $key => $closure_dt) {
					$query = 'ALTER TABLE ' . $table . ' CHANGE ' . $key . ' ' . $closure_dt . ';';
					echo '<pre>' . $query . '</pre>';

					// execute it
					$stmt = self::$connection->prepare($query);
					$executed = $stmt->execute();
				}

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					// Return $this to allow chaining
					return $this;
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
	 * Define bit datatype
	 *
	 * @param mixed $cols
	 */
	public static function bit(mixed $cols = "")
	{
		self::$datatype = "$cols BIT";
		return new self;
	}

	/**
	 * Define tinyint datatype
	 *
	 * @param mixed $cols
	 * @param int $length
	 *
	 */
	public static function tinyint(mixed $cols = "", int $length = 4)
	{
		self::$datatype = "$cols TINYINT($length)";
		return new self;
	}

	/**
	 * Define smallint datatype
	 *
	 * @param mixed $cols
	 * @param int $length
	 *
	 */
	public static function smallint(mixed $cols = "", int $length = 5)
	{
		self::$datatype = "$cols SMALLINT($length)";
		return new self;
	}

	/**
	 * Define mediumint datatype
	 *
	 * @param mixed $cols
	 * @param int $length
	 *
	 */
	public static function mediumint(mixed $cols = "", int $length = 9)
	{
		self::$datatype = "$cols MEDIUMINT($length)";
		return new self;
	}

	/**
	 * Define int datatype
	 *
	 * @param mixed $cols
	 * @param int $length
	 *
	 */
	public static function int(mixed $cols = "", int $length = 11)
	{
		self::$datatype = "$cols INT($length)";
		return new self;
	}

	/**
	 * Define integer datatype
	 *
	 * @param mixed $cols
	 * @param int $length
	 *
	 */
	public static function integer(mixed $cols = "", int $length = 11)
	{
		self::$datatype = "$cols INTEGER($length)";
		return new self;
	}

	/**
	 * Define bigint datatype
	 *
	 * @param mixed $cols
	 * @param int $length
	 *
	 */
	public static function bigint(mixed $cols = "", int $length = 20)
	{
		self::$datatype = "$cols BIGINT($length)";
		return new self;
	}

	/**
	 * Define decimal datatype
	 *
	 * @param mixed $cols
	 * @param int $length
	 * @param int $decimal
	 *
	 */
	public static function decimal(mixed $cols = "", int $length = 10, int $decimal = 0)
	{
		self::$datatype = "$cols DECIMAL($length, $decimal)";
		return new self;
	}

	/**
	 * Define dec datatype
	 *
	 * @param mixed $cols
	 * @param int $length
	 * @param int $decimal
	 *
	 */
	public static function dec(mixed $cols = "", int $length = 10, int $decimal = 0)
	{
		self::$datatype = "$cols DEC($length, $decimal)";
		return new self;
	}

	/**
	 * Define numeric datatype
	 *
	 * @param mixed $cols
	 * @param int $length
	 * @param int $decimal
	 *
	 */
	public static function numeric(mixed $cols = "", int $length = 10, int $decimal = 0)
	{
		self::$datatype = "$cols NUMERIC($length, $decimal)";
		return new self;
	}

	/**
	 * Define fixed datatype
	 *
	 * @param mixed $cols
	 * @param int $length
	 * @param int $decimal
	 *
	 */
	public static function fixed(mixed $cols = "", int $length = 10, int $decimal = 0)
	{
		self::$datatype = "$cols FIXED($length, $decimal)";
		return new self;
	}

	/**
	 * Define float datatype
	 *
	 * @param mixed $cols
	 * @param int $length
	 * @param int $decimal
	 *
	 */
	public static function float(mixed $cols = "", int $length = 10, int $decimal = 0)
	{
		self::$datatype = "$cols FLOAT($length, $decimal)";
		return new self;
	}

	/**
	 * Define float precision datatype
	 *
	 * @param mixed $cols
	 * @param int $precision
	 *
	 */
	public static function float_precision(mixed $cols = "", int $precision = 0)
	{
		self::$datatype = "$cols FLOAT($precision)";
		return new self;
	}

	/**
	 * Define double datatype
	 *
	 * @param mixed $cols
	 * @param int $length
	 * @param int $decimal
	 *
	 */
	public static function double(mixed $cols = "", int $length = 10, int $decimal = 0)
	{
		self::$datatype = "$cols DOUBLE($length, $decimal)";
		return new self;
	}

	/**
	 * Define double precision datatype
	 *
	 * @param mixed $cols
	 * @param int $length
	 * @param int $decimal
	 *
	 */
	public static function double_precision(mixed $cols = "", int $length = 10, int $decimal = 0)
	{
		self::$datatype = "$cols DOUBLE PRECISION($length, $decimal)";
		return new self;
	}

	/**
	 * Define real datatype
	 *
	 * @param mixed $cols
	 * @param int $length
	 * @param int $decimal
	 *
	 */
	public static function real(mixed $cols = "", int $length = 10, int $decimal = 0)
	{
		self::$datatype = "$cols REAL($length, $decimal)";
		return new self;
	}

	/**
	 * Define bool datatype
	 *
	 * @param mixed $cols
	 */
	public static function bool(mixed $cols = "")
	{
		self::$datatype = "$cols BOOL";
		return new self;
	}

	/**
	 * Define boolean datatype
	 *
	 * @param mixed $cols
	 */
	public static function boolean(mixed $cols = "")
	{
		self::$datatype = "$cols BOOLEAN";
		return new self;
	}

	/**
	 * Define char datatype
	 *
	 * @param mixed $cols
	 * @param int $length
	 *
	 */
	public static function char(mixed $cols = "", int $length = 255)
	{
		self::$datatype = "$cols CHAR($length)";
		return new self;
	}

	/**
	 * Define varchar datatype
	 *
	 * @param mixed $cols
	 * @param int $length
	 *
	 */
	public static function varchar(mixed $cols = "", int $length = 255)
	{
		self::$datatype = "$cols VARCHAR($length)";
		return new self;
	}

	/**
	 * Define tinytext datatype
	 *
	 * @param mixed $cols
	 */
	public static function tinytext(mixed $cols = "")
	{
		self::$datatype = "$cols TINYTEXT";
		return new self;
	}

	/**
	 * Define text datatype
	 *
	 * @param mixed $cols
	 */
	public static function text(mixed $cols = "")
	{
		self::$datatype = "$cols TEXT";
		return new self;
	}

	/**
	 * Define mediumtext datatype
	 *
	 * @param mixed $cols
	 */
	public static function mediumtext(mixed $cols = "")
	{
		self::$datatype = "$cols MEDIUMTEXT";
		return new self;
	}

	/**
	 * Define longtext datatype
	 *
	 * @param mixed $cols
	 */
	public static function longtext(mixed $cols = "")
	{
		self::$datatype = "$cols LONGTEXT";
		return new self;
	}

	/**
	 * Define binary datatype
	 *
	 * @param mixed $cols
	 * @param int $length
	 */
	public static function binary(mixed $cols = "", int $length = 255)
	{
		self::$datatype = "$cols BINARY($length)";
		return new self;
	}

	/**
	 * Define varbinary datatype
	 *
	 * @param mixed $cols
	 * @param int $length
	 */
	public static function varbinary(mixed $cols = "", int $length = 255)
	{
		self::$datatype = "$cols VARBINARY($length)";
		return new self;
	}

	/**
	 * Define date datatype
	 *
	 * @param mixed $cols
	 */
	public static function date(mixed $cols = "")
	{
		self::$datatype = "$cols DATE";
		return new self;
	}

	/**
	 * Define datetime datatype
	 *
	 * @param mixed $cols
	 */
	public static function datetime(mixed $cols = "")
	{
		self::$datatype = "$cols DATETIME";
		return new self;
	}

	/**
	 * Define timestamp datatype
	 *
	 * @param mixed $cols
	 * @param int $length
	 */
	public static function timestamp(mixed $cols = "", int $length = 6)
	{
		self::$datatype = "$cols TIMESTAMP($length)";
		return new self;
	}

	/**
	 * Define time datatype
	 *
	 * @param mixed $cols
	 */
	public static function time(mixed $cols = "")
	{
		self::$datatype = "$cols TIME";
		return new self;
	}

	/**
	 * Define year datatype
	 *
	 * @param mixed $cols
	 * @param int $length
	 */
	public static function year(mixed $cols = "", int $length = 2)
	{
		self::$datatype = "$cols YEAR($length)";
		return new self;
	}

	/**
	 * Define tinyblob datatype
	 *
	 * @param mixed $cols
	 */
	public static function tinyblob(mixed $cols = "")
	{
		self::$datatype = "$cols TINYBLOB";
		return new self;
	}

	/**
	 * Define blob datatype
	 *
	 * @param mixed $cols
	 */
	public static function blob(mixed $cols = "")
	{
		self::$datatype = "$cols BLOB";
		return new self;
	}

	/**
	 * Define mediumblob datatype
	 *
	 * @param mixed $cols
	 */
	public static function mediumblob(mixed $cols = "")
	{
		self::$datatype = "$cols MEDIUMBLOB";
		return new self;
	}

	/**
	 * Define not null function
	 */
	public function not_null()
	{
		return self::$datatype . " NOT NULL";
	}

	/**
	 * Define null function
	 */
	public function null()
	{
		return self::$datatype . " NULL";
	}

	/**
	 * Define auto increment function
	 */
	public function auto_increment()
	{
		return self::$datatype . " AUTO_INCREMENT";
	}

	/**
	 * Define default function
	 *
	 * @param mixed $params
	 */
	public function default(mixed $params = '')
	{
		return self::$datatype . " NOT NULL DEFAULT $params";
	}
}
