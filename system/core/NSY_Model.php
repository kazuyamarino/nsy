<?php
namespace Core;

defined('ROOT') OR exit('No direct script access allowed');

class NSY_Model {

	// Declare properties for Helper
	private $connection;
	private $query;
	private $variables;
	private $fetch_style;
	private $bind;
	private $type;
	private $column;
	private $bind_name;
	private $attr;
	private $param;

	/*
	Helper for NSY_Model PDO variables
	 */
	public function __construct() {
		// Define binding variable type
		defined('PARAM_INT') or define('PARAM_INT', \PDO::PARAM_INT);
		defined('PARAM_STR') or define('PARAM_STR', \PDO::PARAM_STR);

		// Define binding type
		defined('BINDVALUE') or define('BINDVALUE', "BINDVALUE");
		defined('BINDPARAM') or define('BINDPARAM', "BINDPARAM");

		defined('FETCH_NUM') or define('FETCH_NUM', \PDO::FETCH_NUM);
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
		    case "mysql":
				$this->connection = NSY_DB::connect_mysql();
				return $this;
		        break;
		    case "dblib":
				$this->connection = NSY_DB::connect_dblib();
				return $this;
		        break;
		    case "sqlsrv":
				$this->connection = NSY_DB::connect_sqlsrv();
				return $this;
		        break;
			case "pgsql":
				$this->connection = NSY_DB::connect_pgsql();
				return $this;
		        break;
		    default:
		        echo "Default database connection not found or undefined, please configure it in .env file 'DB_CONNECTION'.";
		}
	}

	// Secondary Connection
	protected function connect_sec() {
		switch (config_db_sec('secondary', null)) {
		    case "mysql":
				$this->connection = NSY_DB::connect_mysql_sec();
				return $this;
		        break;
		    case "dblib":
				$this->connection = NSY_DB::connect_dblib_sec();
				return $this;
		        break;
		    case "sqlsrv":
				$this->connection = NSY_DB::connect_sqlsrv_sec();
				return $this;
		        break;
			case "pgsql":
				$this->connection = NSY_DB::connect_pgsql_sec();
				return $this;
		        break;
		    default:
		        echo "Default database connection not found or undefined, please configure it in .env file 'DB_CONNECTION'.";
		}
	}

	protected function query($query = null) {
		$this->query = $query;
		return $this;
	}

	protected function vars($variables = null) {
		$this->variables = $variables;
		return $this;
	}

	protected function style($fetch_style = FETCH_BOTH) {
		$this->fetch_style = $fetch_style;
		return $this;
	}

	protected function bind($bind = null) {
		$this->bind = $bind;
		return $this;
	}

	protected function type($type = null) {
		$this->type = $type;
		return $this;
	}

	protected function column($column = 0) {
		$this->column = $column;
		return $this;
	}

	/*
	Helper for NSY_Model to create a sequence of the named placeholders
	 */
	protected function sequence() {
		$in = "";
		foreach ($this->variables as $i => $item)
		{
		    $key = "$this->bind".$i;
		    $in .= "$key,";
		    $in_params[$key] = $item; // collecting values into key-value array
		}
		$in = rtrim($in,","); // :id0,:id1,:id2

		return [$in, $in_params];
	}

	/*
	Helper for PDO FetchAll
	 */
	protected function fetch_all() {
		// Check if there's connection defined on the models
		if ($this->connection == "" || empty($this->connection) || !isset($this->connection)) {
			echo "No Connection Bro, Please check your code again!";
		} else {
			$stmt = $this->connection->prepare($this->query);
			// if vars null, execute queies without vars, else execute it with defined on the models
			if ($this->variables == "" || empty($this->variables) || !isset($this->variables)) {
				$executed = $stmt->execute();
				if (!$executed) {
					$errors = $stmt->errorInfo();
					echo "Error Query : ".($errors[0]);
					echo ", No parameter were bound for query, Please check your query again!";
					exit();
				}
			} else {
				if ($this->bind == "BINDVALUE") {
					if ($this->type == "" || empty($this->type) || !isset($this->type)) {
						echo "BindValue parameter type undefined, for example use PARAM_INT or PARAM_STR";
					} else {
						foreach ($this->variables as $key => $res) {
							$stmt->bindValue($key, $res, $this->type);
						}
						$stmt->execute();
					}
				} elseif ($this->bind == "BINDPARAM") {
					if ($this->type == "" || empty($this->type) || !isset($this->type)) {
						echo "BindParam parameter type undefined, for example use PARAM_INT or PARAM_STR";
					} else {
						foreach ($this->variables as $key => $res) {
							$stmt->bindParam($key, $res, $this->type);
						}
						$stmt->execute();
					}
				} elseif ($this->bind == "" || empty($this->bind) || !isset($this->bind)) {
					$stmt->execute($this->variables);
				}
			}

			// Check the errors, if no errors then return the results
			if ($stmt->errorCode() == 0) {
				$show_result = $stmt->fetchAll($this->fetch_style);

				return $show_result;
			} else {
				// if there's errors, then display the message
				$errors = $stmt->errorInfo();
				echo "Error Query : ".($errors[0].", ".$errors[1].", ".$errors[2]);
				exit();
			}
		}

		// Close the statement & connection
		$stmt = null;
		$db = null;
    }

	/*
	Helper for PDO Fetch
	 */
	protected function fetch() {
		// Check if there's connection defined on the models
		if ($this->connection == "" || empty($this->connection) || !isset($this->connection)) {
			echo "No Connection Bro, Please check your connection again!";
		} else {
			$stmt = $this->connection->prepare($this->query);
			// if vars null, execute queies without vars, else execute it with defined on the models
			if ($this->variables == "" || empty($this->variables) || !isset($this->variables)) {
				$executed = $stmt->execute();
				if (!$executed) {
					$errors = $stmt->errorInfo();
					echo "Error Query : ".($errors[0]);
					echo ", No parameter were bound for query, Please check your query again!";
					exit();
				}
			} else {
				if ($this->bind == "BINDVALUE") {
					if ($this->type == "" || empty($this->type) || !isset($this->type)) {
						echo "BindValue parameter type undefined, for example use PARAM_INT or PARAM_STR";
					} else {
						foreach ($this->variables as $key => $res) {
							$stmt->bindValue($key, $res, $this->type);
						}
						$stmt->execute();
					}
				} elseif ($this->bind == "BINDPARAM") {
					if ($this->type == "" || empty($this->type) || !isset($this->type)) {
						echo "BindParam parameter type undefined, for example use PARAM_INT or PARAM_STR";
					} else {
						foreach ($this->variables as $key => $res) {
							$stmt->bindParam($key, $res, $this->type);
						}
						$stmt->execute();
					}
				} elseif ($this->bind == "" || empty($this->bind) || !isset($this->bind)) {
					$stmt->execute($this->variables);
				}
			}

			// Check the errors, if no errors then return the results
			if ($stmt->errorCode() == 0) {
				$show_result = $stmt->fetch($this->fetch_style);

				return $show_result;
			} else {
				// if there's errors, then display the message
				$errors = $stmt->errorInfo();
				echo "Error Query : ".($errors[0].", ".$errors[1].", ".$errors[2]);
				exit();
			}
		}

		// Close the statement & connection
		$stmt = null;
		$db = null;
    }

	/*
	Helper for PDO FetchColumn
	 */
	protected function fetch_column() {
		// Check if there's connection defined on the models
		if ($this->connection == "" || empty($this->connection) || !isset($this->connection)) {
			echo "No Connection Bro, Please check your connection again!";
		} else {
			$stmt = $this->connection->prepare($this->query);
			// if vars null, execute queies without vars, else execute it with defined on the models
			if ($this->variables == "" || empty($this->variables) || !isset($this->variables)) {
				$executed = $stmt->execute();
				if (!$executed) {
					$errors = $stmt->errorInfo();
					echo "Error Query : ".($errors[0]);
					echo ", No parameter were bound for query, Please check your query again!";
					exit();
				}
			} else {
				if ($this->bind == "BINDVALUE") {
					if ($this->type == "" || empty($this->type) || !isset($this->type)) {
						echo "BindValue parameter type undefined, for example use PARAM_INT or PARAM_STR";
					} else {
						foreach ($this->variables as $key => $res) {
							$stmt->bindValue($key, $res, $this->type);
						}
						$stmt->execute();
					}
				} elseif ($this->bind == "BINDPARAM") {
					if ($this->type == "" || empty($this->type) || !isset($this->type)) {
						echo "BindParam parameter type undefined, for example use PARAM_INT or PARAM_STR";
					} else {
						foreach ($this->variables as $key => $res) {
							$stmt->bindParam($key, $res, $this->type);
						}
						$stmt->execute();
					}
				} elseif ($this->bind == "" || empty($this->bind) || !isset($this->bind)) {
					$stmt->execute($this->variables);
				}
			}

			// Check the errors, if no errors then return the results
			if ($stmt->errorCode() == 0) {
				$show_result = $stmt->fetchColumn($this->column);

				return $show_result;
			} else {
				// if there's errors, then display the message
				$errors = $stmt->errorInfo();
				echo "Error Query : ".($errors[0].", ".$errors[1].", ".$errors[2]);
				exit();
			}
		}

		// Close the statement & connection
		$stmt = null;
		$db = null;
    }

	/*
	Helper for PDO RowCount
	 */
	protected function row_count() {
		// Check if there's connection defined on the models
		if ($this->connection == "" || empty($this->connection) || !isset($this->connection)) {
			echo "No Connection Bro, Please check your connection again!";
		} else {
			$stmt = $this->connection->prepare($this->query);
			// if vars null, execute queies without vars, else execute it with defined on the models
			if ($this->variables == "" || empty($this->variables) || !isset($this->variables)) {
				$executed = $stmt->execute();
				if (!$executed) {
					$errors = $stmt->errorInfo();
					echo "Error Query : ".($errors[0]);
					echo ", No parameter were bound for query, Please check your query again!";
					exit();
				}
			} else {
				$stmt->execute($this->variables);
			}

			// Check the errors, if no errors then return the results
			if ($stmt->errorCode() == 0) {
				$show_result = $stmt->rowCount();

				return $show_result;
			} else {
				// if there's errors, then display the message
				$errors = $stmt->errorInfo();
				echo "Error Query : ".($errors[0].", ".$errors[1].", ".$errors[2]);
				exit();
			}
		}

		// Close the statement & connection
		$stmt = null;
		$db = null;
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
				if ($this->connection == "" || empty($this->connection) || !isset($this->connection)) {
					echo "No Connection Bro, Please check your connection again!";
				} else {
					$stmt = $this->connection->prepare($this->query);
					// if vars null, execute queies without vars, else execute it with defined on the models
					if ($this->variables == "" || empty($this->variables) || !isset($this->variables)) {
						$executed = $stmt->execute();
						if (!$executed) {
							$errors = $stmt->errorInfo();
							echo "Error Query : ".($errors[0]);
							echo ", No parameter were bound for query, Please check your query again!";
							exit();
						}
					} else {
						$executed = $stmt->execute($this->variables);
					}

					// Check the errors, if no errors then return the results
					if ($stmt->errorCode() == 0) {
						return $executed;
					} else {
						// if there's errors, then display the message
						$errors = $stmt->errorInfo();
						echo "Error Query : ".($errors[0].", ".$errors[1].", ".$errors[2]);
						exit();
					}
				}
			    $result = 'CSRF check passed. Form parsed.';
			}
			catch ( \Exception $e ) {
			    // CSRF attack detected
			    echo $result = $e->getMessage() . ' Form ignored.';
			}
		} elseif(config_app('csrf_token') === 'false') {
			// Check if there's connection defined on the models
			if ($this->connection == "" || empty($this->connection) || !isset($this->connection)) {
				echo "No Connection Bro, Please check your connection again!";
			} else {
				$stmt = $this->connection->prepare($this->query);
				// if vars null, execute queies without vars, else execute it with defined on the models
				if ($this->variables == "" || empty($this->variables) || !isset($this->variables)) {
					$executed = $stmt->execute();
					if (!$executed) {
						$errors = $stmt->errorInfo();
						echo "Error Query : ".($errors[0]);
						echo ", No parameter were bound for query, Please check your query again!";
						exit();
					}
				} else {
					$executed = $stmt->execute($this->variables);
				}

				// Check the errors, if no errors then return the results
				if ($stmt->errorCode() == 0) {
					return $executed;
				} else {
					// if there's errors, then display the message
					$errors = $stmt->errorInfo();
					echo "Error Query : ".($errors[0].", ".$errors[1].", ".$errors[2]);
					exit();
				}
			}
		} else {
			echo 'The CSRF Token Protection is not set correctly. Please check in the system/config/app.php';
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		$db = null;
    }

	/*
	Helper for PDO Multi Execute
	 */
	protected function multi_exec() {
		if(config_app('csrf_token') === 'true') {
			try {
			    // Run CSRF check, on POST data, in exception mode, for 10 minutes, in one-time mode.
			    \NSY_CSRF::check( 'csrf_token', $_POST, true, 60*10, false );

				// Check if there's connection defined on the models
				if ($this->connection == "" || empty($this->connection) || !isset($this->connection)) {
					echo "No Connection Bro, Please check your connection again!";
				} else {
					$stmt = $this->connection->prepare($this->query);
					// if vars null, execute queies without vars, else execute it with defined on the models
					if ($this->variables == "" || empty($this->variables) || !isset($this->variables)) {
						$executed = $stmt->execute();
						if (!$executed) {
							$errors = $stmt->errorInfo();
							echo "Error Query : ".($errors[0]);
							echo ", No parameter were bound for query, Please check your query again!";
							exit();
						}
					} else {
						foreach($this->variables as $key => $params) {
							$executed = $stmt->execute(array($params));
						}
					}

					// Check the errors, if no errors then return the results
					if ($stmt->errorCode() == 0) {
						return $executed;
					} else {
						// if there's errors, then display the message
						$errors = $stmt->errorInfo();
						echo "Error Query : ".($errors[0].", ".$errors[1].", ".$errors[2]);
						exit();
					}
				}
				$result = 'CSRF check passed. Form parsed.';
			}
			catch ( \Exception $e ) {
				// CSRF attack detected
				echo $result = $e->getMessage() . ' Form ignored.';
			}
		} elseif(config_app('csrf_token') === 'false') {
			// Check if there's connection defined on the models
			if ($this->connection == "" || empty($this->connection) || !isset($this->connection)) {
				echo "No Connection Bro, Please check your connection again!";
			} else {
				$stmt = $this->connection->prepare($this->query);
				// if vars null, execute queies without vars, else execute it with defined on the models
				if ($this->variables == "" || empty($this->variables) || !isset($this->variables)) {
					$executed = $stmt->execute();
					if (!$executed) {
						$errors = $stmt->errorInfo();
						echo "Error Query : ".($errors[0]);
						echo ", No parameter were bound for query, Please check your query again!";
						exit();
					}
				} else {
					foreach($this->variables as $key => $params) {
						$executed = $stmt->execute(array($params));
					}
				}

				// Check the errors, if no errors then return the results
				if ($stmt->errorCode() == 0) {
					return $executed;
				} else {
					// if there's errors, then display the message
					$errors = $stmt->errorInfo();
					echo "Error Query : ".($errors[0].", ".$errors[1].", ".$errors[2]);
					exit();
				}
			}
		} else {
			echo 'The CSRF Token Protection is not set correctly. Please check in the system/config/app.php';
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		$db = null;
    }

	/*
	Helper for PDO Emulation False
	 */
	protected function emulate_prepares_false() {
		$this->connection->setAttribute(\PDO::ATTR_EMULATE_PREPARES, FALSE);
    }

	/*
	Helper for PDO MYSQL_ATTR_USE_BUFFERED_QUERY
	 */
	protected function use_buffer_query_true() {
		$this->connection->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, TRUE);
    }

	protected function use_buffer_query_false() {
		$this->connection->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, FALSE);
    }

	/*
	Helper for PDO ATTR_STRINGIFY_FETCHES
	 */
	protected function stringify_fetches_true() {
		$this->connection->setAttribute(\PDO::ATTR_STRINGIFY_FETCHES, TRUE);
    }

	/*
	Helper for PDO Begin Transaction
	 */
	protected function begin_trans() {
		$this->connection->beginTransaction();
    }

	/*
	Helper for PDO Commit Transaction
	 */
	protected function end_trans() {
		$this->connection->commit();
    }

	/*
	Helper for PDO Rollback Transaction
	 */
	protected function rollback_trans() {
		$this->connection->rollback();
    }

	/*
 	Function for basic field validation (present and neither empty nor only white space
 	 */
 	protected function not_filled($str = '') {
		if (!empty($str)) {
			return false;
			exit();
		} else {
	  		if (is_array($str)) {
				return (!isset($str) || empty($str));
	  		} else {
	  			return (!isset($str) || $str == '' || empty($str));
	  		}
		}
  	}

 	/*
 	Function for basic field validation (present and neither filled nor not empty)
 	 */
 	protected function is_filled($str = '') {
		if (!isset($str)) {
			return false;
			exit();
		} else {
			if (is_array($str)) {
				return (isset($key) || !empty($str));
	  		} else {
				return (isset($key) || !empty($str));
	  		}
		}
  	}

}
