<?php
namespace System\Core;

/**
* This is the core of NSY Model
* Attention, don't try to change the structure of the code, delete, or change.
* Because there is some code connected to the NSY system. So, be careful.
*/
class DB
{

	// Declare properties for Helper
	static $connection;
	static $query;
	static $variables;
	static $fetch_style;
	static $bind;
	static $column;
	static $bind_name;
	static $attr;
	static $param;
	static $num;
	static $result;
	static $executed;

	/**
	* Default Connection
	*
	* @param string $conn_name
	* @return object
	*/
	public static function connect($conn_name = 'primary')
	{
		switch ( config_db($conn_name, 'DB_CONNECTION') ) {
			case 'mysql':
			self::$connection = NSY_DB::connect_mysql($conn_name);
			return new self;
			break;
			case 'dblib':
			self::$connection = NSY_DB::connect_dblib($conn_name);
			return new self;
			break;
			case 'sqlsrv':
			self::$connection = NSY_DB::connect_sqlsrv($conn_name);
			return new self;
			break;
			case 'pgsql':
			self::$connection = NSY_DB::connect_pgsql($conn_name);
			return new self;
			break;
			default:
			$var_msg = "Default database connection not found or undefined, please configure it in <strong>.env</strong> file <strong><i>DB_CONNECTION</i></strong>";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}
	}

	/**
	* Function as a query declaration
	*
	* @param  string $query
	* @return string
	*/
	public function query($query = '')
	{
		if (is_filled($query) ) {
			self::$query = $query;
		} else
		{
			$var_msg = "The value of query in the <mark>query(<strong>value</strong>)</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		return new self;
	}

	/**
	* Function as a variable container
	*
	* @param  array $variables
	* @return array
	*/
	public function vars($variables = array())
	{
		if (is_array($variables) || is_object($variables) ) {
			self::$variables = $variables;
		} else
		{
			$var_msg = "The variable in the <mark>vars(<strong>variables</strong>)</mark> is improper or not an array";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		return new self;
	}

	/**
	* Function as a fetch style declaration
	*
	* @param  string $fetch_style
	* @return string
	*/
	public function style($fetch_style = FETCH_BOTH)
	{
		if (is_filled($fetch_style) ) {
			self::$fetch_style = $fetch_style;
		} else
		{
			$var_msg = "The value of style in the <mark>style(<strong>value</strong>)</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		return new self;
	}

	/**
	* Start method for variables sequence (bind)
	*
	* @param  string $bind
	* @return string
	*/
	public function bind($bind = '')
	{
		if (is_filled($bind) ) {
			self::$bind = $bind;
		} else
		{
			$var_msg = "The value that binds in the <mark>bind(<strong>value</strong>)->vars()->sequence()</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		return new self;
	}

	/**
	* Method for fetch column (column)
	*
	* @param  int $column
	* @return int
	*/
	public function column($column = 0)
	{
		if (is_filled($column) ) {
			self::$column = $column;
		} else
		{
			$var_msg = "The value of column in the <mark>column(<strong>value</strong>)</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		return new self;
	}

	/**
	* Helper for PDO FetchAll
	*/
	public function fetch_all()
	{
		// Check if there's connection defined on the models
		if (not_filled(self::$connection) ) {
			echo '<pre>No Connection, Please check your connection again!</pre>';
			exit();
		} else {
			$stmt = self::$connection->prepare(self::$query);
			// if vars null, execute queries without vars, else execute it with defined on the models
			if (not_filled(self::$variables) ) {
				$executed = $stmt->execute();
			} else {
				if (self::$bind == 'BINDVALUE') {
					if (is_array(self::$variables) || is_object(self::$variables)) {
						foreach (self::$variables as $key => &$res) {
							if (not_filled($res[1]) || not_filled($res[0]) ) {
								$var_msg = 'BindValue parameter type undefined, for example use PAR_INT or PAR_STR in the <strong>null</strong> variable.<br><br>['.$key.' => ['.$res[0].', <strong>null</strong>] ]';
								NSY_Desk::static_error_handler($var_msg);
								exit();
							} else {
								$stmt->bindValue($key, $res[0], $res[1]);
							}
						}

						$executed = $stmt->execute();
					}
				} elseif (self::$bind == 'BINDPARAM') {
					if (is_array(self::$variables) || is_object(self::$variables)) {
						foreach (self::$variables as $key => &$res) {
							if (not_filled($res[1]) || not_filled($res[0]) ) {
								$var_msg = 'BindParam parameter type undefined, for example use PAR_INT or PAR_STR in the <strong>null</strong> variable.<br><br>['.$key.' => ['.$res[0].', <strong>null</strong>] ]';
								NSY_Desk::static_error_handler($var_msg);
								exit();
							} else {
								$stmt->bindParam($key, $res[0], $res[1]);
							}
						}

						$executed = $stmt->execute();
					}
				} else {
					$executed = $stmt->execute(self::$variables);
				}
			}

			// Check the errors, if no errors then return the results
			if ($executed || $stmt->errorCode() == 0) {
				$show_result = $stmt->fetchAll(self::$fetch_style ?? '');

				return $show_result;
			} else {
				$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
				NSY_Desk::static_error_handler($var_msg);
				exit();
			}
		}

		// Close the statement & connection
		$stmt = null;
		self::$connection = null;
	}

	/**
	* Helper for PDO Fetch
	*/
	public function fetch()
	{
		// Check if there's connection defined on the models
		if (not_filled(self::$connection) ) {
			echo '<pre>No Connection, Please check your connection again!</pre>';
			exit();
		} else {
			$stmt = self::$connection->prepare(self::$query);
			// if vars null, execute queries without vars, else execute it with defined on the models
			if (not_filled(self::$variables) ) {
				$executed = $stmt->execute();
			} else {
				if (self::$bind == 'BINDVALUE') {
					if (is_array(self::$variables) || is_object(self::$variables)) {
						foreach (self::$variables as $key => &$res) {
							if (not_filled($res[1]) || not_filled($res[0]) ) {
								$var_msg = 'BindValue parameter type undefined, for example use PAR_INT or PAR_STR in the <strong>null</strong> variable.<br><br>['.$key.' => ['.$res[0].', <strong>null</strong>] ]';
								NSY_Desk::static_error_handler($var_msg);
								exit();
							} else {
								$stmt->bindValue($key, $res[0], $res[1]);
							}
						}

						$executed = $stmt->execute();
					}
				} elseif (self::$bind == 'BINDPARAM') {
					if (is_array(self::$variables) || is_object(self::$variables)) {
						foreach (self::$variables as $key => &$res) {
							if (not_filled($res[1]) || not_filled($res[0]) ) {
								$var_msg = 'BindParam parameter type undefined, for example use PAR_INT or PAR_STR in the <strong>null</strong> variable.<br><br>['.$key.' => ['.$res[0].', <strong>null</strong>] ]';
								NSY_Desk::static_error_handler($var_msg);
								exit();
							} else {
								$stmt->bindParam($key, $res[0], $res[1]);
							}
						}

						$executed = $stmt->execute();
					}
				} else {
					$executed = $stmt->execute(self::$variables);
				}
			}

			// Check the errors, if no errors then return the results
			if ($executed || $stmt->errorCode() == 0) {
				$show_result = $stmt->fetch(self::$fetch_style ?? '');

				return $show_result;
			} else {
				$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
				NSY_Desk::static_error_handler($var_msg);
				exit();
			}
		}

		// Close the statement & connection
		$stmt = null;
		self::$connection = null;
	}

	/**
	* Helper for PDO FetchColumn
	*/
	public function fetch_column()
	{
		// Check if there's connection defined on the models
		if (not_filled(self::$connection) ) {
			echo '<pre>No Connection, Please check your connection again!</pre>';
			exit();
		} else {
			$stmt = self::$connection->prepare(self::$query);
			// if vars null, execute queries without vars, else execute it with defined on the models
			if (not_filled(self::$variables) ) {
				$executed = $stmt->execute();
			} else {
				if (self::$bind == 'BINDVALUE') {
					if (is_array(self::$variables) || is_object(self::$variables)) {
						foreach (self::$variables as $key => &$res) {
							if (not_filled($res[1]) || not_filled($res[0]) ) {
								$var_msg = 'BindValue parameter type undefined, for example use PAR_INT or PAR_STR in the <strong>null</strong> variable.<br><br>['.$key.' => ['.$res[0].', <strong>null</strong>] ]';
								NSY_Desk::static_error_handler($var_msg);
								exit();
							} else {
								$stmt->bindValue($key, $res[0], $res[1]);
							}
						}

						$executed = $stmt->execute();
					}
				} elseif (self::$bind == 'BINDPARAM') {
					if (is_array(self::$variables) || is_object(self::$variables)) {
						foreach (self::$variables as $key => &$res) {
							if (not_filled($res[1]) || not_filled($res[0]) ) {
								$var_msg = 'BindParam parameter type undefined, for example use PAR_INT or PAR_STR in the <strong>null</strong> variable.<br><br>['.$key.' => ['.$res[0].', <strong>null</strong>] ]';
								NSY_Desk::static_error_handler($var_msg);
								exit();
							} else {
								$stmt->bindParam($key, $res[0], $res[1]);
							}
						}

						$executed = $stmt->execute();
					}
				} else {
					$executed = $stmt->execute(self::$variables);
				}
			}

			// Check the errors, if no errors then return the results
			if ($executed || $stmt->errorCode() == 0) {
				$show_result = $stmt->fetchColumn(self::$column ?? 0);

				return $show_result;
			} else {
				$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
				NSY_Desk::static_error_handler($var_msg);
				exit();
			}
		}

		// Close the statement & connection
		$stmt = null;
		self::$connection = null;
	}

	/**
	* Helper for PDO RowCount
	*/
	public function row_count()
	{
		// Check if there's connection defined on the models
		if (not_filled(self::$connection) ) {
			echo '<pre>No Connection, Please check your connection again!</pre>';
			exit();
		} else {
			// if vars null, execute queries without vars, else execute it with defined on the models
			if (not_filled(self::$variables) ) {
				$stmt = self::$connection->prepare(self::$query);
				$stmt->execute();
				self::$result = $stmt->rowCount();
			} else {
				$arr_keys = array_keys(self::$variables);
				foreach ( $arr_keys as $dt ) {
					if (is_numeric($dt) ) {
						$var_msg = "Array keys doesn't exist on <mark>vars(<strong>variables</strong>)</mark>";
						NSY_Desk::static_error_handler($var_msg);
						exit();
					}
				}

				$stmt = self::$connection->prepare(self::$query);
				$stmt->execute(self::$variables);
				self::$result = $stmt->rowCount();
			}

			// Check the errors, if no errors then return the results
			if (self::$result || $stmt->errorCode() == 0) {
				return self::$result;
			} else {
				if(config_app('transaction') === 'on') {
					self::$connection->rollback();

					if (not_filled(self::$variables) ) {
						$var_msg = "Syntax error or access violation! \nNo parameter were bound for query, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
						exit();
					} else {
						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
						exit();
					}
				} elseif(config_app('transaction') === 'off') {
					if (not_filled(self::$variables) ) {
						$var_msg = "Syntax error or access violation! \nNo parameter were bound for query, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
						exit();
					} else {
						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
						exit();
					}
				} else {
					echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
					exit();
				}
			}
		}

		// Close the statement & connection
		$stmt = null;
		self::$connection = null;
	}

	/**
	* Helper for PDO Execute
	*/
	public function exec()
	{
		if(config_app('csrf_token') === 'true') {
			try {
				// Run CSRF check, on POST data, in exception mode, for 10 minutes, in one-time mode.
				csrf_check('csrf_token', $_POST, true, 60*10, false);

				// Check if there's connection defined on the models
				if (not_filled(self::$connection) ) {
					echo '<pre>No Connection, Please check your connection again!</pre>';
					exit();
				} else {
					// if vars null, execute queries without vars, else execute it with defined on the models
					if (not_filled(self::$variables) ) {
						$stmt = self::$connection->prepare(self::$query);
						self::$executed = $stmt->execute();
					} else {
						$stmt = self::$connection->prepare(self::$query);
						self::$executed = $stmt->execute(self::$variables);
					}

					// Check the errors, if no errors then return the results
					if (self::$executed || $stmt->errorCode() == 0) {
						return new self;
					} else {
						if(config_app('transaction') === 'on') {
							self::$connection->rollback();

							if (not_filled(self::$variables) ) {
								$var_msg = "Syntax error or access violation! \nNo parameter were bound for query, \nPlease check your query again!";
								NSY_Desk::static_error_handler($var_msg);
								exit();
							} else {
								$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
								NSY_Desk::static_error_handler($var_msg);
								exit();
							}
						} elseif(config_app('transaction') === 'off') {
							if (not_filled(self::$variables) ) {
								$var_msg = "Syntax error or access violation! \nNo parameter were bound for query, \nPlease check your query again!";
								NSY_Desk::static_error_handler($var_msg);
								exit();
							} else {
								$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
								NSY_Desk::static_error_handler($var_msg);
								exit();
							}
						} else {
							echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
							exit();
						}
					}
				}
				$result = '<pre>CSRF check passed. Form parsed.</pre>'; // Just info
			}
			catch ( \Exception $e ) {
				// CSRF attack detected
				echo $result = '<pre>' . $e->getMessage() . ' Form ignored.</pre>'; // Just info
				exit();
			}
		} elseif(config_app('csrf_token') === 'false') {
			// Check if there's connection defined on the models
			if (not_filled(self::$connection) ) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				// if vars null, execute queries without vars, else execute it with defined on the models
				if (not_filled(self::$variables) ) {
					$stmt = self::$connection->prepare(self::$query);
					self::$executed = $stmt->execute();
				} else {
					$stmt = self::$connection->prepare(self::$query);
					self::$executed = $stmt->execute(self::$variables);
				}

				// Check the errors, if no errors then return the results
				if (self::$executed || $stmt->errorCode() == 0) {
					return new self;
				} else {
					if(config_app('transaction') === 'on') {
						self::$connection->rollback();

						if (not_filled(self::$variables) ) {
							$var_msg = "Syntax error or access violation! \nNo parameter were bound for query, \nPlease check your query again!";
							NSY_Desk::static_error_handler($var_msg);
							exit();
						} else {
							$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
							NSY_Desk::static_error_handler($var_msg);
							exit();
						}
					} elseif(config_app('transaction') === 'off') {
						if (not_filled(self::$variables) ) {
							$var_msg = "Syntax error or access violation! \nNo parameter were bound for query, \nPlease check your query again!";
							NSY_Desk::static_error_handler($var_msg);
							exit();
						} else {
							$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
							NSY_Desk::static_error_handler($var_msg);
							exit();
						}
					} else {
						echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
						exit();
					}
				}
			}
		} else {
			echo '<pre>The CSRF Token Protection is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		self::$connection = null;
	}

	/**
	* Helper for PDO Multi Insert
	*/
	public function multi_insert()
	{
		if(config_app('csrf_token') === 'true') {
			try {
				// Run CSRF check, on POST data, in exception mode, for 10 minutes, in one-time mode.
				csrf_check('csrf_token', $_POST, true, 60*10, false);

				// Check if there's connection defined on the models
				if (not_filled(self::$connection) ) {
					echo '<pre>No Connection, Please check your connection again!</pre>';
					exit();
				} else {
					$rows = count(self::$variables);
					$cols = count(self::$variables[0]);
					$rowString = '(' . rtrim(str_repeat('?,', $cols), ',') . '),';
					$valString = rtrim(str_repeat($rowString, $rows), ',');

					// if vars null, execute queries without vars, else execute it with defined on the models
					if (not_filled(self::$variables) ) {
						$var_msg = "Syntax error or access violation! \nNo parameter were bound for query, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
						exit();
					} else {
						$stmt = self::$connection->prepare(self::$query . ' VALUES '. $valString);

						$bindArray = array();
						array_walk_recursive(
							self::$variables, function ($item) use (&$bindArray) {
								$bindArray[] = $item;
							}
						);
						self::$executed = $stmt->execute($bindArray);
					}

					// Check the errors, if no errors then return the results
					if (self::$executed || $stmt->errorCode() == 0) {
						return new self;
					} else {
						if(config_app('transaction') === 'on') {
							self::$connection->rollback();
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
				$result = '<pre>CSRF check passed. Form parsed.</pre>'; // Just info
			}
			catch ( \Exception $e ) {
				// CSRF attack detected
				echo $result = '<pre>' . $e->getMessage() . ' Form ignored.</pre>'; // Just info
				exit();
			}
		} elseif(config_app('csrf_token') === 'false') {
			// Check if there's connection defined on the models
			if (not_filled(self::$connection) ) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				$rows = count(self::$variables);
				$cols = count(self::$variables[0]);
				$rowString = '(' . rtrim(str_repeat('?,', $cols), ',') . '),';
				$valString = rtrim(str_repeat($rowString, $rows), ',');

				// if vars null, execute queries without vars, else execute it with defined on the models
				if (not_filled(self::$variables) ) {
					$var_msg = "Syntax error or access violation! \nNo parameter were bound for query, \nPlease check your query again!";
					NSY_Desk::static_error_handler($var_msg);
					exit();
				} else {
					$stmt = self::$connection->prepare(self::$query . ' VALUES '. $valString);

					$bindArray = array();
					array_walk_recursive(
						self::$variables, function ($item) use (&$bindArray) {
							$bindArray[] = $item;
						}
					);
					self::$executed = $stmt->execute($bindArray);
				}

				// Check the errors, if no errors then return the results
				if ($stmt->errorCode() == 0) {
					return new self;
				} else {
					if(config_app('transaction') === 'on') {
						// if there's errors, then display the message
						self::$connection->rollback();
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
		} else {
			echo '<pre>The CSRF Token Protection is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		self::$connection = null;
	}

	/**
	* Helper for PDO Emulation False
	*/
	public function emulate_prepares_false()
	{
		self::$connection->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
		return new self;
	}

	/**
	* Helper for PDO MYSQL_ATTR_USE_BUFFERED_QUERY
	*/
	public function use_buffer_query_true()
	{
		self::$connection->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
		return new self;
	}

	public function use_buffer_query_false()
	{
		self::$connection->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
		return new self;
	}

	/**
	* Helper for PDO ATTR_STRINGIFY_FETCHES
	*/
	public function stringify_fetches_true()
	{
		self::$connection->setAttribute(\PDO::ATTR_STRINGIFY_FETCHES, true);
		return new self;
	}

	/**
	* Helper for PDO Begin Transaction
	*/
	public function begin_trans()
	{
		self::$connection->beginTransaction();
		return new self;
	}
	/**
	* Helper for PDO Commit Transaction
	*/
	public function end_trans()
	{
		self::$connection->commit();
		return new self;
	}
	/**
	* Helper for PDO Rollback Transaction
	*/
	public function null_trans()
	{
		self::$connection->rollback();
		return new self;
	}

}
