<?php
namespace Core;

defined('ROOT') OR exit('No direct script access allowed');

use Core\NSY_Desk;
/*
 * This is the core of NSY Model
 * 2018 - Vikry Yuansah
 * Attention, don't try to change the structure of the code, delete, or change. Because there is some code connected to the NSY system. So, be careful.
 */
class NSY_Model extends NSY_Desk {

	// Declare properties for Helper
	private $connection;
	private $query;
	private $variables;
	private $fetch_style;
	private $bind;
	private $column;
	private $bind_name;
	private $attr;
	private $param;
	private $num;

	/*
	Helper for NSY_Model PDO variables
	 */
	public function __construct() {
		// Define binding variable type
		defined('PAR_INT') or define('PAR_INT', \PDO::PARAM_INT);
		defined('PAR_STR') or define('PAR_STR', \PDO::PARAM_STR);

		// Define binding type
		defined('BINDVAL') or define('BINDVAL', "BINDVALUE");
		defined('BINDPAR') or define('BINDPAR', "BINDPARAM");

		defined('FETCH_NUM') or define('FETCH_NUM', \PDO::FETCH_NUM);
		defined('FETCH_COLUMN') or define('FETCH_COLUMN', \PDO::FETCH_COLUMN);
		defined('FETCH_ASSOC') or define('FETCH_ASSOC', \PDO::FETCH_ASSOC);
		defined('FETCH_BOTH') or define('FETCH_BOTH', \PDO::FETCH_BOTH);
		defined('FETCH_OBJ') or define('FETCH_OBJ', \PDO::FETCH_OBJ);
		defined('FETCH_LAZY') or define('FETCH_LAZY', \PDO::FETCH_LAZY);
		defined('FETCH_CLASS') or define('FETCH_CLASS', \PDO::FETCH_CLASS);
		defined('FETCH_KEY_PAIR') or define('FETCH_KEY_PAIR', \PDO::FETCH_KEY_PAIR);
		defined('FETCH_UNIQUE') or define('FETCH_UNIQUE', \PDO::FETCH_UNIQUE);
		defined('FETCH_GROUP') or define('FETCH_GROUP', \PDO::FETCH_GROUP);
		defined('FETCH_FUNC') or define('FETCH_FUNC', \PDO::FETCH_FUNC);
	}

	// Default Connection
	protected function connect() {
		switch (config_db('default', null)) {
		    case 'mysql':
				$this->connection = NSY_DB::connect_mysql();
				return $this;
		        break;
		    case 'dblib':
				$this->connection = NSY_DB::connect_dblib();
				return $this;
		        break;
		    case 'sqlsrv':
				$this->connection = NSY_DB::connect_sqlsrv();
				return $this;
		        break;
			case 'pgsql':
				$this->connection = NSY_DB::connect_pgsql();
				return $this;
		        break;
		    default:
				$var_msg = "Default database connection not found or undefined, please configure it in <strong>.env</strong> file <strong><i>DB_CONNECTION</i></strong>";
				$this->error_handler($var_msg);
				exit();
		}
	}

	// Secondary Connection
	protected function connect_sec() {
		switch (config_db_sec('secondary', null)) {
		    case 'mysql':
				$this->connection = NSY_DB::connect_mysql_sec();
				return $this;
		        break;
		    case 'dblib':
				$this->connection = NSY_DB::connect_dblib_sec();
				return $this;
		        break;
		    case 'sqlsrv':
				$this->connection = NSY_DB::connect_sqlsrv_sec();
				return $this;
		        break;
			case 'pgsql':
				$this->connection = NSY_DB::connect_pgsql_sec();
				return $this;
		        break;
		    default:
				$var_msg = "Second database connection not found or undefined, please configure it in <strong>.env</strong> file <strong><i>DB_CONNECTION_SEC</i></strong>";
				$this->error_handler($var_msg);
				exit();
		}
	}

	protected function query($query = null) {
		if ( is_filled($query) )
		{
			$this->query = $query;
		} else
		{
			$var_msg = "The value of query in the <mark>query(<strong>value</strong>)</mark> is empty or undefined";
			$this->error_handler($var_msg);
			exit();
		}

		return $this;
	}

	protected function vars($variables = array()) {
		if ( is_array($variables) || is_object($variables) )
		{
 			$this->variables = $variables;
		} else
		{
			$var_msg = "The variable in the <mark>vars(<strong>variables</strong>)</mark> is improper or not an array";
			$this->error_handler($var_msg);
			exit();
		}

 		return $this;
	}

	protected function style($fetch_style = FETCH_BOTH) {
		if ( is_filled($fetch_style) )
		{
			$this->fetch_style = $fetch_style;
		} else
		{
			$var_msg = "The value of style in the <mark>style(<strong>value</strong>)</mark> is empty or undefined";
			$this->error_handler($var_msg);
			exit();
		}

		return $this;
	}

	protected function bind($bind = null) {
		if ( is_filled($bind) )
		{
			$this->bind = $bind;
		} else
		{
			$var_msg = "The value that binds in the <mark>bind(<strong>value</strong>)->vars()->sequence()</mark> is empty or undefined";
			$this->error_handler($var_msg);
			exit();
		}

		return $this;
	}

	protected function column($column = 0) {
		if ( is_filled($column) )
		{
			$this->column = $column;
		} else
		{
			$var_msg = "The value of column in the <mark>column(<strong>value</strong>)</mark> is empty or undefined";
			$this->error_handler($var_msg);
			exit();
		}

		return $this;
	}

	/*
	Helper for NSY_Model to create a sequence of the named placeholders
	 */
	protected function sequence() {
		$in = '';
		if ( is_array($this->variables) || is_object($this->variables) )
		{
			foreach ($this->variables as $i => $item)
			{
			    $key = $this->bind.$i;
			    $in .= $key.',';
			    $in_params[$key] = $item; // collecting values into key-value array
			}
		} else
		{
			$var_msg = "The variable in the <mark>vars(<strong>variables</strong>)->sequence()</mark> is improper or not an array";
			$this->error_handler($var_msg);
			exit();
		}
		$in = rtrim($in,','); // example = :id0,:id1,:id2

		return [$in, $in_params];
 	}

	/*
	Helper for PDO FetchAll
	 */
	protected function fetch_all() {
		// Check if there's connection defined on the models
		if ( not_filled($this->connection) ) {
			echo '<pre>No Connection, Please check your connection again!</pre>';
			exit();
		} else {
			$stmt = $this->connection->prepare($this->query);
			// if vars null, execute queries without vars, else execute it with defined on the models
			if ( not_filled($this->variables) ) {
				$executed = $stmt->execute();
			} else {
				if ($this->bind == 'BINDVALUE') {
					if (is_array($this->variables) || is_object($this->variables))
					{
						foreach ($this->variables as $key => &$res) {
							if ( not_filled($res[1]) || not_filled($res[0]) ) {
								$var_msg = 'BindValue parameter type undefined, for example use PAR_INT or PAR_STR in the <strong>null</strong> variable.<br><br>['.$key.' => ['.$res[0].', <strong>null</strong>] ]';
								$this->error_handler($var_msg);
								exit();
							} else {
								$stmt->bindValue($key, $res[0], $res[1]);
							}
						}

						$executed = $stmt->execute();
					}
				} elseif ($this->bind == 'BINDPARAM') {
					if (is_array($this->variables) || is_object($this->variables))
					{
						foreach ($this->variables as $key => &$res) {
							if ( not_filled($res[1]) || not_filled($res[0]) ) {
								$var_msg = 'BindParam parameter type undefined, for example use PAR_INT or PAR_STR in the <strong>null</strong> variable.<br><br>['.$key.' => ['.$res[0].', <strong>null</strong>] ]';
								$this->error_handler($var_msg);
								exit();
							} else {
								$stmt->bindParam($key, $res[0], $res[1]);
							}
						}

						$executed = $stmt->execute();
					}
				} else {
					$executed = $stmt->execute($this->variables);
				}
			}

			// Check the errors, if no errors then return the results
			if ($executed || $stmt->errorCode() == 0) {
				$show_result = $stmt->fetchAll($this->fetch_style);

				return $show_result;
			} else {
				$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
				$this->error_handler($var_msg);
				exit();
			}
		}

		// Close the statement & connection
		$stmt = null;
		$this->connection = null;
    }

	/*
	Helper for PDO Fetch
	 */
	protected function fetch() {
		// Check if there's connection defined on the models
		if ( not_filled($this->connection) ) {
			echo '<pre>No Connection, Please check your connection again!</pre>';
			exit();
		} else {
			$stmt = $this->connection->prepare($this->query);
			// if vars null, execute queries without vars, else execute it with defined on the models
			if ( not_filled($this->variables) ) {
				$executed = $stmt->execute();
			} else {
				if ($this->bind == 'BINDVALUE') {
					if (is_array($this->variables) || is_object($this->variables))
					{
						foreach ($this->variables as $key => &$res) {
							if ( not_filled($res[1]) || not_filled($res[0]) ) {
								$var_msg = 'BindValue parameter type undefined, for example use PAR_INT or PAR_STR in the <strong>null</strong> variable.<br><br>['.$key.' => ['.$res[0].', <strong>null</strong>] ]';
								$this->error_handler($var_msg);
								exit();
							} else {
								$stmt->bindValue($key, $res[0], $res[1]);
							}
						}

						$executed = $stmt->execute();
					}
				} elseif ($this->bind == 'BINDPARAM') {
					if (is_array($this->variables) || is_object($this->variables))
					{
						foreach ($this->variables as $key => &$res) {
							if ( not_filled($res[1]) || not_filled($res[0]) ) {
								$var_msg = 'BindParam parameter type undefined, for example use PAR_INT or PAR_STR in the <strong>null</strong> variable.<br><br>['.$key.' => ['.$res[0].', <strong>null</strong>] ]';
								$this->error_handler($var_msg);
								exit();
							} else {
								$stmt->bindParam($key, $res[0], $res[1]);
							}
						}

						$executed = $stmt->execute();
					}
				} else {
					$executed = $stmt->execute($this->variables);
				}
			}

			// Check the errors, if no errors then return the results
			if ($executed || $stmt->errorCode() == 0) {
				$show_result = $stmt->fetch($this->fetch_style);

				return $show_result;
			} else {
				$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
				$this->error_handler($var_msg);
				exit();
			}
		}

		// Close the statement & connection
		$stmt = null;
		$this->connection = null;
    }

	/*
	Helper for PDO FetchColumn
	 */
	protected function fetch_column() {
		// Check if there's connection defined on the models
		if ( not_filled($this->connection) ) {
			echo '<pre>No Connection, Please check your connection again!</pre>';
			exit();
		} else {
			$stmt = $this->connection->prepare($this->query);
			// if vars null, execute queries without vars, else execute it with defined on the models
			if ( not_filled($this->variables) ) {
				$executed = $stmt->execute();
			} else {
				if ($this->bind == 'BINDVALUE') {
					if (is_array($this->variables) || is_object($this->variables))
					{
						foreach ($this->variables as $key => &$res) {
							if ( not_filled($res[1]) || not_filled($res[0]) ) {
								$var_msg = 'BindValue parameter type undefined, for example use PAR_INT or PAR_STR in the <strong>null</strong> variable.<br><br>['.$key.' => ['.$res[0].', <strong>null</strong>] ]';
								$this->error_handler($var_msg);
								exit();
							} else {
								$stmt->bindValue($key, $res[0], $res[1]);
							}
						}

						$executed = $stmt->execute();
					}
				} elseif ($this->bind == 'BINDPARAM') {
					if (is_array($this->variables) || is_object($this->variables))
					{
						foreach ($this->variables as $key => &$res) {
							if ( not_filled($res[1]) || not_filled($res[0]) ) {
								$var_msg = 'BindParam parameter type undefined, for example use PAR_INT or PAR_STR in the <strong>null</strong> variable.<br><br>['.$key.' => ['.$res[0].', <strong>null</strong>] ]';
								$this->error_handler($var_msg);
								exit();
							} else {
								$stmt->bindParam($key, $res[0], $res[1]);
							}
						}

						$executed = $stmt->execute();
					}
				} else {
					$executed = $stmt->execute($this->variables);
				}
			}

			// Check the errors, if no errors then return the results
			if ($executed || $stmt->errorCode() == 0) {
				$show_result = $stmt->fetchColumn($this->column);

				return $show_result;
			} else {
				$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
				$this->error_handler($var_msg);
				exit();
			}
		}

		// Close the statement & connection
		$stmt = null;
		$this->connection = null;
    }

	/*
	Helper for PDO RowCount
	 */
	protected function row_count() {
		// Check if there's connection defined on the models
		if ( not_filled($this->connection) ) {
			echo '<pre>No Connection, Please check your connection again!</pre>';
			exit();
		} else {
			// if vars null, execute queries without vars, else execute it with defined on the models
			if ( not_filled($this->variables) ) {
				$stmt = $this->connection->prepare($this->query);
				$stmt->execute();
				$this->result = $stmt->rowCount();
			} else {
				$arr_keys = array_keys($this->variables);
				foreach ( $arr_keys as $dt ) {
					if ( is_numeric($dt) ) {
						$var_msg = "Array keys doesn't exist on <mark>vars(<strong>variables</strong>)</mark>";
						$this->error_handler($var_msg);
						exit();
					}
				}

				$stmt = $this->connection->prepare($this->query);
				$stmt->execute($this->variables);
				$this->result = $stmt->rowCount();
			}

			// Check the errors, if no errors then return the results
			if ($this->result || $stmt->errorCode() == 0) {
				return $this->result;
			} else {
				if(config_app('transaction') === 'on') {
					$this->connection->rollback();

					if ( not_filled($this->variables) ) {
						$var_msg = "Syntax error or access violation! \nNo parameter were bound for query, \nPlease check your query again!";
						$this->error_handler($var_msg);
						exit();
					} else {
						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						$this->error_handler($var_msg);
						exit();
					}
				} elseif(config_app('transaction') === 'off') {
					if ( not_filled($this->variables) ) {
						$var_msg = "Syntax error or access violation! \nNo parameter were bound for query, \nPlease check your query again!";
						$this->error_handler($var_msg);
						exit();
					} else {
						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						$this->error_handler($var_msg);
						exit();
					}
				} else {
					echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>system/config/app.php</i></strong></pre>';
					exit();
				}
			}
		}

		// Close the statement & connection
		$stmt = null;
		$this->connection = null;
    }

	/*
	Helper for PDO Execute
	 */
	protected function exec() {
		if(config_app('csrf_token') === 'true') {
			try {
			    // Run CSRF check, on POST data, in exception mode, for 10 minutes, in one-time mode.
			    \NSY_CSRF::check( 'csrf_token', $_POST, true, 60*10, false );

				// Check if there's connection defined on the models
				if ( not_filled($this->connection) ) {
					echo '<pre>No Connection, Please check your connection again!</pre>';
					exit();
				} else {
					// if vars null, execute queries without vars, else execute it with defined on the models
					if ( not_filled($this->variables) ) {
						$stmt = $this->connection->prepare($this->query);
						$this->executed = $stmt->execute();
					} else {
						$stmt = $this->connection->prepare($this->query);
						$this->executed = $stmt->execute($this->variables);
					}

					// Check the errors, if no errors then return the results
					if ($this->executed || $stmt->errorCode() == 0) {
						return $this;
					} else {
						if(config_app('transaction') === 'on') {
							$this->connection->rollback();

							if ( not_filled($this->variables) ) {
								$var_msg = "Syntax error or access violation! \nNo parameter were bound for query, \nPlease check your query again!";
								$this->error_handler($var_msg);
								exit();
							} else {
								$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
								$this->error_handler($var_msg);
								exit();
							}
						} elseif(config_app('transaction') === 'off') {
							if ( not_filled($this->variables) ) {
								$var_msg = "Syntax error or access violation! \nNo parameter were bound for query, \nPlease check your query again!";
								$this->error_handler($var_msg);
								exit();
							} else {
								$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
								$this->error_handler($var_msg);
								exit();
							}
						} else {
							echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>system/config/app.php</i></strong></pre>';
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
			if ( not_filled($this->connection) ) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				// if vars null, execute queries without vars, else execute it with defined on the models
				if ( not_filled($this->variables) ) {
					$stmt = $this->connection->prepare($this->query);
					$this->executed = $stmt->execute();
				} else {
					$stmt = $this->connection->prepare($this->query);
					$this->executed = $stmt->execute($this->variables);
				}

				// Check the errors, if no errors then return the results
				if ($this->executed || $stmt->errorCode() == 0) {
					return $this;
				} else {
					if(config_app('transaction') === 'on') {
						$this->connection->rollback();

						if ( not_filled($this->variables) ) {
							$var_msg = "Syntax error or access violation! \nNo parameter were bound for query, \nPlease check your query again!";
							$this->error_handler($var_msg);
							exit();
						} else {
							$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
							$this->error_handler($var_msg);
							exit();
						}
					} elseif(config_app('transaction') === 'off') {
						if ( not_filled($this->variables) ) {
							$var_msg = "Syntax error or access violation! \nNo parameter were bound for query, \nPlease check your query again!";
							$this->error_handler($var_msg);
							exit();
						} else {
							$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
							$this->error_handler($var_msg);
							exit();
						}
					} else {
						echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>system/config/app.php</i></strong></pre>';
						exit();
					}
				}
			}
		} else {
			echo '<pre>The CSRF Token Protection is not set correctly. Please check in the <strong><i>system/config/app.php</i></strong></pre>';
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		$this->connection = null;
    }

	/*
	Helper for PDO Multi Insert
	 */
	protected function multi_insert() {
		if(config_app('csrf_token') === 'true') {
			try {
			    // Run CSRF check, on POST data, in exception mode, for 10 minutes, in one-time mode.
			    \NSY_CSRF::check( 'csrf_token', $_POST, true, 60*10, false );

				// Check if there's connection defined on the models
				if ( not_filled($this->connection) ) {
					echo '<pre>No Connection, Please check your connection again!</pre>';
					exit();
				} else {
					$rows = count($this->variables);
					$cols = count($this->variables[0]);
					$rowString = '(' . rtrim(str_repeat('?,', $cols), ',') . '),';
					$valString = rtrim(str_repeat($rowString, $rows), ',');

					// if vars null, execute queries without vars, else execute it with defined on the models
					if ( not_filled($this->variables) ) {
						$var_msg = "Syntax error or access violation! \nNo parameter were bound for query, \nPlease check your query again!";
						$this->error_handler($var_msg);
						exit();
					} else {
						$stmt = $this->connection->prepare($this->query . ' VALUES '. $valString);

						$bindArray = array();
						array_walk_recursive($this->variables, function($item) use (&$bindArray) { $bindArray[] = $item; });
						$this->executed = $stmt->execute($bindArray);
					}

					// Check the errors, if no errors then return the results
					if ($this->executed || $stmt->errorCode() == 0) {
						return $this;
					} else {
						if(config_app('transaction') === 'on') {
							$this->connection->rollback();
							$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
							$this->error_handler($var_msg);
							exit();
						} elseif(config_app('transaction') === 'off') {
							$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
							$this->error_handler($var_msg);
							exit();
						} else {
							echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>system/config/app.php</i></strong></pre>';
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
			if ( not_filled($this->connection) ) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				$rows = count($this->variables);
				$cols = count($this->variables[0]);
				$rowString = '(' . rtrim(str_repeat('?,', $cols), ',') . '),';
				$valString = rtrim(str_repeat($rowString, $rows), ',');

				// if vars null, execute queries without vars, else execute it with defined on the models
				if ( not_filled($this->variables) ) {
					$var_msg = "Syntax error or access violation! \nNo parameter were bound for query, \nPlease check your query again!";
					$this->error_handler($var_msg);
					exit();
				} else {
					$stmt = $this->connection->prepare($this->query . ' VALUES '. $valString);

					$bindArray = array();
					array_walk_recursive($this->variables, function($item) use (&$bindArray) { $bindArray[] = $item; });
					$this->executed = $stmt->execute($bindArray);
				}

				// Check the errors, if no errors then return the results
				if ($stmt->errorCode() == 0) {
					return $this;
				} else {
					if(config_app('transaction') === 'on') {
						// if there's errors, then display the message
						$this->connection->rollback();
						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						$this->error_handler($var_msg);
						exit();
					} elseif(config_app('transaction') === 'off') {
						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						$this->error_handler($var_msg);
						exit();
					} else {
						echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>system/config/app.php</i></strong></pre>';
						exit();
					}
				}
			}
		} else {
			echo '<pre>The CSRF Token Protection is not set correctly. Please check in the <strong><i>system/config/app.php</i></strong></pre>';
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		$this->connection = null;
    }

	/*
	Helper for PDO Emulation False
	 */
	protected function emulate_prepares_false() {
		$this->connection->setAttribute(\PDO::ATTR_EMULATE_PREPARES, FALSE);
		return $this;
    }

	/*
	Helper for PDO MYSQL_ATTR_USE_BUFFERED_QUERY
	 */
	protected function use_buffer_query_true() {
		$this->connection->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, TRUE);
		return $this;
    }

	protected function use_buffer_query_false() {
		$this->connection->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, FALSE);
		return $this;
    }

	/*
	Helper for PDO ATTR_STRINGIFY_FETCHES
	 */
	protected function stringify_fetches_true() {
		$this->connection->setAttribute(\PDO::ATTR_STRINGIFY_FETCHES, TRUE);
		return $this;
    }

	/*
	Helper for PDO Begin Transaction
	 */
	protected function begin_trans() {
		$this->connection->beginTransaction();
		return $this;
    }
	/*
	Helper for PDO Commit Transaction
	 */
	protected function end_trans() {
		$this->connection->commit();
		return $this;
    }
	/*
	Helper for PDO Rollback Transaction
	 */
	protected function null_trans() {
		$this->connection->rollback();
		return $this;
    }

}
