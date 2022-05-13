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
	private $connection;

	/**
	* Default Connection
	*
	* @return void
	*/
	protected function connect($conn_name = 'primary')
	{
		switch ( config_env($conn_name, 'DB_CONNECTION') ) {
			case 'mysql':
			$this->connection = NSY_DB::connect_mysql($conn_name);
			return $this;
			break;
			case 'dblib':
			$this->connection = NSY_DB::connect_dblib($conn_name);
			return $this;
			break;
			case 'sqlsrv':
			$this->connection = NSY_DB::connect_sqlsrv($conn_name);
			return $this;
			break;
			case 'pgsql':
			$this->connection = NSY_DB::connect_pgsql($conn_name);
			return $this;
			break;
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
	protected function create_db($db = null)
	{
		if (is_filled($db) ) {
			$query = "CREATE DATABASE $db;";
			echo '<pre>'. $query . '</pre>';

			// Check if there's connection defined on the models
			if (not_filled($this->connection) ) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				// execute it
				$stmt = $this->connection->prepare($query);
				$executed = $stmt->execute();

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					return $executed;
				} else {
					if(config_app('transaction') === 'on') {
						$this->connection->rollback();

						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
						exit();
					} elseif(config_app('transaction') === 'off') {
						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
						exit();
					} else {
						echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
						exit();
					}
				}
			}
		} else
		{
			$var_msg = "Database name in the <mark>create_db(<strong>value</strong>)</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		$this->connection = null;
	}

	/**
	* Function for delete database
	*
	* @param string $db
	*/
	protected function drop_db($db = null)
	{
		if (is_filled($db) ) {
			$query = "DROP DATABASE $db;";
			echo '<pre>'. $query . '</pre>';

			// Check if there's connection defined on the models
			if (not_filled($this->connection) ) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				// execute it
				$stmt = $this->connection->prepare($query);
				$executed = $stmt->execute();

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					return $executed;
				} else {
					if(config_app('transaction') === 'on') {
						$this->connection->rollback();

						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
						exit();
					} elseif(config_app('transaction') === 'off') {
						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
						exit();
					} else {
						echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
						exit();
					}
				}
			}
		} else
		{
			$var_msg = "Database name in the <mark>drop_db(<strong>value</strong>)</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		$this->connection = null;
	}

	/**
	* Function for rename table (mysql/mariadb)
	*
	* @param string $old_table
	* @param string $new_table
	*/
	protected function rename_table($old_table = null, $new_table = null)
	{
		if (is_filled($old_table) || is_filled($new_table) ) {
			$query = "RENAME TABLE $old_table TO $new_table;";
			echo '<pre>'. $query . '</pre>';

			// Check if there's connection defined on the models
			if (not_filled($this->connection) ) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				// execute it
				$stmt = $this->connection->prepare($query);
				$executed = $stmt->execute();

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					return $executed;
				} else {
					if(config_app('transaction') === 'on') {
						$this->connection->rollback();

						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
						exit();
					} elseif(config_app('transaction') === 'off') {
						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
						exit();
					} else {
						echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
						exit();
					}
				}
			}
		} else
		{
			$var_msg = "Table name in the <mark>rename_table(<strong>old_table</strong>, <strong>new_table</strong>)</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		$this->connection = null;
	}

	/**
	* Function for alter rename table (postgre)
	*
	* @param string $old_table
	* @param string $new_table
	*/
	protected function alter_rename_table($old_table = null, $new_table = null)
	{
		if (is_filled($old_table) || is_filled($new_table) ) {
			$query = "ALTER TABLE $old_table RENAME TO $new_table;";
			echo '<pre>'. $query . '</pre>';

			// Check if there's connection defined on the models
			if (not_filled($this->connection) ) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				// execute it
				$stmt = $this->connection->prepare($query);
				$executed = $stmt->execute();

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					return $executed;
				} else {
					if(config_app('transaction') === 'on') {
						$this->connection->rollback();

						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
						exit();
					} elseif(config_app('transaction') === 'off') {
						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
						exit();
					} else {
						echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
						exit();
					}
				}
			}
		} else
		{
			$var_msg = "Table name in the <mark>alter_rename_table(<strong>old_table</strong>, <strong>new_table</strong>)</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		$this->connection = null;
	}

	/**
	* Function for sp rename table (mssql)
	*
	* @param string $old_table
	* @param string $new_table
	*/
	protected function sp_rename_table($old_table = null, $new_table = null)
	{
		if (is_filled($old_table) || is_filled($new_table) ) {
			$query = "sp_rename '$old_table', '$new_table';";
			echo '<pre>'. $query . '</pre>';

			// Check if there's connection defined on the models
			if (not_filled($this->connection) ) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				// execute it
				$stmt = $this->connection->prepare($query);
				$executed = $stmt->execute();

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					return $executed;
				} else {
					if(config_app('transaction') === 'on') {
						$this->connection->rollback();

						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
						exit();
					} elseif(config_app('transaction') === 'off') {
						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
						exit();
					} else {
						echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
						exit();
					}
				}
			}
		} else
		{
			$var_msg = "Table name in the <mark>sp_rename_table(<strong>old_table</strong>, <strong>new_table</strong>)</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		$this->connection = null;
	}

	/**
	* Function for delete table
	*
	* @param string $table
	*/
	protected function drop_table($table = null)
	{
		if (is_filled($table) ) {
			$query = "DROP TABLE $table;";
			echo '<pre>'. $query . '</pre>';

			// Check if there's connection defined on the models
			if (not_filled($this->connection) ) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				// execute it
				$stmt = $this->connection->prepare($query);
				$executed = $stmt->execute();

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					return $executed;
				} else {
					if(config_app('transaction') === 'on') {
						$this->connection->rollback();

						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
						exit();
					} elseif(config_app('transaction') === 'off') {
						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
						exit();
					} else {
						echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
						exit();
					}
				}
			}
		} else
		{
			$var_msg = "Table name in the <mark>drop_table(<strong>value</strong>)</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		$this->connection = null;
	}

	/**
	* Function for delete table if exist
	*
	* @param string $table
	*/
	protected function drop_exist_table($table = null)
	{
		if (is_filled($table) ) {
			$query = "DROP TABLE IF EXISTS $table;";
			echo '<pre>'. $query . '</pre>';

			// Check if there's connection defined on the models
			if (not_filled($this->connection) ) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				// execute it
				$stmt = $this->connection->prepare($query);
				$executed = $stmt->execute();

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					return $executed;
				} else {
					if(config_app('transaction') === 'on') {
						$this->connection->rollback();

						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
						exit();
					} elseif(config_app('transaction') === 'off') {
						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
						exit();
					} else {
						echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
						exit();
					}
				}
			}
		} else
		{
			$var_msg = "Table name in the <mark>drop_exist_table(<strong>value</strong>)</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		$this->connection = null;
	}

	/**
	* Define Primary Key
	*
	* @param  array $cols
	* @return string
	*/
	protected function primary($cols = array())
	{
		if (is_array($cols) || is_object($cols) ) {
			$res = array();
			foreach ( $cols as $key => $col ) {
				$res[] = $col;
			}
			$im_cols = implode(', ', $res);
			$con_cols = implode('_', $res);

			return $this->primary = 'CONSTRAINT '. $con_cols . '_' . generate_num(1, 5, 6) .'_PK PRIMARY KEY (' . $im_cols . ')';
		} else
		{
			return $this->primary = 'CONSTRAINT '. $cols . '_' . generate_num(1, 5, 6) .'_PK PRIMARY KEY (' . $cols . ')';
		}
	}

	/**
	* Define Unique Key
	*
	* @param  array $cols
	* @return string
	*/
	protected function unique($cols = array())
	{
		if (is_array($cols) || is_object($cols) ) {
			$res = array();
			foreach ( $cols as $key => $col ) {
				$res[] = $col;
			}
			$im_cols = implode(', ', $res);
			$con_cols = implode('_', $res);

			return $this->primary = 'CONSTRAINT '. $con_cols . '_' . generate_num(1, 5, 6) .'_UN UNIQUE (' . $im_cols . ')';
		} else
		{
			return $this->primary = 'CONSTRAINT '. $cols . '_' . generate_num(1, 5, 6) .'_UN UNIQUE (' . $cols . ')';
		}
	}

	/**
	* Columns with created, update, additional date
	*
	* @return array
	*/
	protected function timestamps()
	{
		$arr_date_cols = [
			'create_date' => 'datetime',
			'update_date' => 'datetime',
			'additional_date' => 'datetime'
		];

		return $arr_date_cols;
	}

	/**
	* Columns with user defined variables
	*
	* @param array $cols
	* @param array $other_cols
	* @param array $other_cols_2
	*/
	protected function cols($cols = array(), $other_cols = array(), $other_cols_2 = array())
	{
		if (is_array($cols) || is_object($cols) ) {
			if (is_filled($other_cols) || is_filled($other_cols_2) ) {
				$res_merge_arr = array_merge($cols, $other_cols, $other_cols_2);
				return $res_merge_arr;
			} else {
				return $cols;
			}
		} else
		{
			$var_msg = "The variable in the <mark>cols(<strong>value</strong>)</mark> is improper or not an array";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}
	}

	/**
	* Function for create table
	*
	* @param string  $table
	* @param Closure $closure
	*/
	protected function create_table($table = null, \Closure $closure)
	{
		if (is_filled($table) ) {
			$closure_res = array();
			foreach ( $closure() as $key => $closure_dt ) {
				if (strpos($closure_dt, 'PRIMARY') || strpos($closure_dt, 'UNIQUE') ) {
					$closure_res[] = $closure_dt;
				} else {
					$closure_res[] = $key . ' ' . $closure_dt;
				}
			}
			$closure_imp = implode(",\n", $closure_res) . "\n";

			$query = "CREATE TABLE " . $table . " ( ". $closure_imp ." );\n";
			echo '<pre>'. $query . '</pre>';

			// Check if there's connection defined on the models
			if (not_filled($this->connection) ) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				// execute it
				$stmt = $this->connection->prepare($query);
				$executed = $stmt->execute();

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					return $executed;
				} else {
					if(config_app('transaction') === 'on') {
						$this->connection->rollback();

						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
						exit();
					} elseif(config_app('transaction') === 'off') {
						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
						exit();
					} else {
						echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
						exit();
					}
				}
			}
		} else
		{
			$var_msg = "Table name in the <mark>create_table(<strong>table</strong>, value)</mark> and \nColumns in the <mark>create_table(table, <strong>value</strong>)</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		$this->connection = null;
	}

	/**
	* Function for add column (mssql)
	*
	* @param string  $table
	* @param Closure $closure
	*/
	protected function add($table = null, \Closure $closure)
	{
		if (is_filled($table) ) {
			$closure_res = array();
			foreach ( $closure() as $key => $closure_dt ) {
				$closure_res[] = 'ALTER TABLE ' . $table . ' ADD ' . $key . ' ' . $closure_dt;
			}
			$closure_imp = implode(";\n", $closure_res) . ";\n";

			$query = $closure_imp;
			echo '<pre>'. $query . '</pre>';

			// Check if there's connection defined on the models
			if (not_filled($this->connection) ) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				// execute it
				$stmt = $this->connection->prepare($query);
				$executed = $stmt->execute();

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					return $executed;
				} else {
					if(config_app('transaction') === 'on') {
						$this->connection->rollback();

						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
						exit();
					} elseif(config_app('transaction') === 'off') {
						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
						exit();
					} else {
						echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
						exit();
					}
				}
			}
		} else
		{
			$var_msg = "Table name in the <mark>add(<strong>table</strong>, value)</mark> and \nColumns in the <mark>add(table, <strong>value</strong>)</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		$this->connection = null;
	}

	/**
	* Function for add column (mysql/mariadb/postgre)
	*
	* @param string  $table
	* @param Closure $closure
	*/
	protected function add_cols($table = null, \Closure $closure)
	{
		if (is_filled($table) ) {
			$closure_res = array();
			foreach ( $closure() as $key => $closure_dt ) {
				$closure_res[] = 'ALTER TABLE ' . $table . ' ADD COLUMN ' . $key . ' ' . $closure_dt;
			}
			$closure_imp = implode(";\n", $closure_res) . ";\n";

			$query = $closure_imp;
			echo '<pre>'. $query . '</pre>';

			// Check if there's connection defined on the models
			if (not_filled($this->connection) ) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				// execute it
				$stmt = $this->connection->prepare($query);
				$executed = $stmt->execute();

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					return $executed;
				} else {
					if(config_app('transaction') === 'on') {
						$this->connection->rollback();

						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
						exit();
					} elseif(config_app('transaction') === 'off') {
						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
						exit();
					} else {
						echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
						exit();
					}
				}
			}
		} else
		{
			$var_msg = "Table name in the <mark>add_cols(<strong>table</strong>, value)</mark> and \nColumns in the <mark>add_cols(table, <strong>value</strong>)</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		$this->connection = null;
	}

	/**
	* Function for drop column (mysql/mariadb/postgre/mssql)
	*
	* @param string  $table
	* @param Closure $closure
	*/
	protected function drop_cols($table = null, \Closure $closure)
	{
		if (is_filled($table) ) {
			$closure_res = array();
			foreach ( $closure() as $key => $closure_dt ) {
				$closure_res[] = 'ALTER TABLE ' . $table . ' DROP COLUMN ' . $closure_dt;
			}
			$closure_imp = implode(";\n", $closure_res) . ";\n";

			$query = $closure_imp;
			echo '<pre>'. $query . '</pre>';

			// Check if there's connection defined on the models
			if (not_filled($this->connection) ) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				// execute it
				$stmt = $this->connection->prepare($query);
				$executed = $stmt->execute();

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					return $executed;
				} else {
					if(config_app('transaction') === 'on') {
						$this->connection->rollback();

						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
						exit();
					} elseif(config_app('transaction') === 'off') {
						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
						exit();
					} else {
						echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
						exit();
					}
				}
			}
		} else
		{
			$var_msg = "Table name in the <mark>drop_cols(<strong>table</strong>, value)</mark> and \nColumns in the <mark>drop_cols(table, <strong>value</strong>)</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		$this->connection = null;
	}

	/**
	* Function for alter column (mssql/postgre)
	*
	* @param string  $table
	* @param Closure $closure
	*/
	protected function alter_cols($table = null, \Closure $closure)
	{
		if (is_filled($table) ) {
			$closure_res = array();
			foreach ( $closure() as $key => $closure_dt ) {
				if (strpos($closure_dt, 'PRIMARY') || strpos($closure_dt, 'UNIQUE') ) {
					$closure_res[] = 'ALTER TABLE ' . $table . ' ADD ' . $closure_dt;
				} else {
					$closure_res[] = 'ALTER TABLE ' . $table . ' ALTER COLUMN ' . $key . ' ' . $closure_dt;
				}
			}
			$closure_imp = implode(";\n", $closure_res) . ";\n";

			$query = $closure_imp;
			echo '<pre>'. $query . '</pre>';

			// Check if there's connection defined on the models
			if (not_filled($this->connection) ) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				// execute it
				$stmt = $this->connection->prepare($query);
				$executed = $stmt->execute();

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					return $executed;
				} else {
					if(config_app('transaction') === 'on') {
						$this->connection->rollback();

						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
						exit();
					} elseif(config_app('transaction') === 'off') {
						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
						exit();
					} else {
						echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
						exit();
					}
				}
			}
		} else
		{
			$var_msg = "Table name in the <mark>alter_cols(<strong>table</strong>, value)</mark> and \nColumns in the <mark>alter_cols(table, <strong>value</strong>)</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		$this->connection = null;
	}

	/**
	* Function for modify column (mysql/mariadb)
	*
	* @param string  $table
	* @param Closure $closure
	*/
	protected function modify_cols($table = null, \Closure $closure)
	{
		if (is_filled($table) ) {
			$closure_res = array();
			foreach ( $closure() as $key => $closure_dt ) {
				if (strpos($closure_dt, 'PRIMARY') || strpos($closure_dt, 'UNIQUE') ) {
					$closure_res[] = 'ALTER TABLE ' . $table . ' ADD ' . $closure_dt;
				} else {
					$closure_res[] = 'ALTER TABLE ' . $table . ' MODIFY COLUMN ' . $key . ' ' . $closure_dt;
				}
			}
			$closure_imp = implode(";\n", $closure_res) . ";\n";

			$query = $closure_imp;
			echo '<pre>'. $query . '</pre>';

			// Check if there's connection defined on the models
			if (not_filled($this->connection) ) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				// execute it
				$stmt = $this->connection->prepare($query);
				$executed = $stmt->execute();

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					return $executed;
				} else {
					if(config_app('transaction') === 'on') {
						$this->connection->rollback();

						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
						exit();
					} elseif(config_app('transaction') === 'off') {
						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
						exit();
					} else {
						echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
						exit();
					}
				}
			}
		} else
		{
			$var_msg = "Table name in the <mark>modify_cols(<strong>table</strong>, value)</mark> and \nColumns in the <mark>modify_cols(table, <strong>value</strong>)</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		$this->connection = null;
	}

	/**
	* Function for rename column (postgre)
	*
	* @param string  $table
	* @param Closure $closure
	*/
	protected function rename_cols($table = null, \Closure $closure)
	{
		if (is_filled($table) ) {
			$closure_res = array();
			foreach ( $closure() as $key => $closure_dt ) {
				$closure_res[] = 'ALTER TABLE ' . $table . ' RENAME COLUMN ' . $key . ' TO ' . $closure_dt;
			}
			$closure_imp = implode(";\n", $closure_res) . ";\n";

			$query = $closure_imp;
			echo '<pre>'. $query . '</pre>';

			// Check if there's connection defined on the models
			if (not_filled($this->connection) ) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				// execute it
				$stmt = $this->connection->prepare($query);
				$executed = $stmt->execute();

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					return $executed;
				} else {
					if(config_app('transaction') === 'on') {
						$this->connection->rollback();

						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
						exit();
					} elseif(config_app('transaction') === 'off') {
						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
						exit();
					} else {
						echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
						exit();
					}
				}
			}
		} else
		{
			$var_msg = "Table name in the <mark>rename_cols(<strong>table</strong>, value)</mark> and \nColumns in the <mark>rename_cols(table, <strong>value</strong>)</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		$this->connection = null;
	}

	/**
	* Function for change column (mysql/mariadb)
	*
	* @param string  $table
	* @param Closure $closure
	*/
	protected function change_cols($table = null, \Closure $closure)
	{
		if (is_filled($table) ) {
			$closure_res = array();
			foreach ( $closure() as $key => $closure_dt ) {
				$closure_res[] = 'ALTER TABLE ' . $table . ' CHANGE ' . $key . ' ' . $closure_dt;
			}
			$closure_imp = implode(";\n", $closure_res) . ";\n";

			$query = $closure_imp;
			echo '<pre>'. $query . '</pre>';

			// Check if there's connection defined on the models
			if (not_filled($this->connection) ) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				// execute it
				$stmt = $this->connection->prepare($query);
				$executed = $stmt->execute();

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					return $executed;
				} else {
					if(config_app('transaction') === 'on') {
						$this->connection->rollback();

						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
						exit();
					} elseif(config_app('transaction') === 'off') {
						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
						exit();
					} else {
						echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
						exit();
					}
				}
			}
		} else
		{
			$var_msg = "Table name in the <mark>change_cols(<strong>table</strong>, value)</mark> and \nColumns in the <mark>change_cols(table, <strong>value</strong>)</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		$this->connection = null;
	}

	/**
	* Function for sp rename column (mssql)
	*
	* @param string  $table
	* @param Closure $closure
	*/
	protected function sp_rename_cols($table = null, \Closure $closure)
	{
		if (is_filled($table) ) {
			$closure_res = array();
			foreach ( $closure() as $key => $closure_dt ) {
				$closure_res[] = "exec sp_rename '" . $table . "." . $key . "', '" . $closure_dt . "', 'COLUMN'";
			}
			$closure_imp = implode(";\n", $closure_res) . ";\n";

			$query = $closure_imp;
			echo '<pre>'. $query . '</pre>';

			// Check if there's connection defined on the models
			if (not_filled($this->connection) ) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				// execute it
				$stmt = $this->connection->prepare($query);
				$executed = $stmt->execute();

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					return $executed;
				} else {
					if(config_app('transaction') === 'on') {
						$this->connection->rollback();

						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
						exit();
					} elseif(config_app('transaction') === 'off') {
						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
						exit();
					} else {
						echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
						exit();
					}
				}
			}
		} else
		{
			$var_msg = "Table name in the <mark>sp_rename_cols(<strong>table</strong>, value)</mark> and \nColumns in the <mark>sp_rename_cols(table, <strong>value</strong>)</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		$this->connection = null;
	}

}
