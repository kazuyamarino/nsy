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
	static $datatype;

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
			'additional_date DATETIME DEFAULT CURRENT_TIMESTAMP'
		];

		return $arr_date_cols;
	}

	/**
	 * Columns with user defined variables
	 *
	 * @param array $cols
	 * @param array $timestamps_cols
	 */
	public static function cols($cols = array(), $timestamps_mark = 'enabled')
	{
		$timestamps_cols = self::timestamps();

		if (is_array($cols) || is_object($cols)) {
			if ( $timestamps_mark == 'enabled' ) {
				if (is_filled($timestamps_cols)) {
					$merge_cols = array_merge($cols, $timestamps_cols);
					return $merge_cols;
				} else {
					return $cols;
				}
			} elseif ( $timestamps_mark == 'disabled' ) {
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
					$closure_res[] = $closure_dt;
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
			foreach ($closure() as $closure_dt) {
				$closure_res[] = 'ALTER TABLE ' . $table . ' ADD ' . ' ' . $closure_dt;
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
			foreach ($closure() as $closure_dt) {
				$closure_res[] = 'ALTER TABLE ' . $table . ' ADD COLUMN ' . ' ' . $closure_dt;
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
			foreach ($closure() as $closure_dt) {
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
			foreach ($closure() as $closure_dt) {
				if (strpos($closure_dt, 'PRIMARY') || strpos($closure_dt, 'UNIQUE')) {
					$closure_res[] = 'ALTER TABLE ' . $table . ' ADD ' . $closure_dt;
				} else {
					$closure_res[] = 'ALTER TABLE ' . $table . ' MODIFY COLUMN ' . ' ' . $closure_dt;
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
			foreach ($closure() as $closure_dt) {
				$closure_res[] = "exec sp_rename '" . $table . "." . "', '" . $closure_dt . "', 'COLUMN'";
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
