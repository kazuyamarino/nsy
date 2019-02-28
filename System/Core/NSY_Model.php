<?php

namespace Core;

defined('ROOT') OR exit('No direct script access allowed');

use Core\NSY_DB;

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
		define('PARAM_INT', \PDO::PARAM_INT);
		define('PARAM_STR', \PDO::PARAM_STR);

		// Define binding type
		define('BINDVALUE', "BINDVALUE");
		define('BINDPARAM', "BINDPARAM");

		define('FETCH_NUM', \PDO::FETCH_NUM);
		define('FETCH_ASSOC', \PDO::FETCH_ASSOC);
		define('FETCH_BOTH', \PDO::FETCH_BOTH);
		define('FETCH_OBJ', \PDO::FETCH_OBJ);
		define('FETCH_LAZY', \PDO::FETCH_LAZY);
		define('FETCH_CLASS', \PDO::FETCH_CLASS);
		define('FETCH_KEY_PAIR', \PDO::FETCH_KEY_PAIR);
		define('FETCH_UNIQUE', \PDO::FETCH_UNIQUE);
		define('FETCH_GROUP', \PDO::FETCH_GROUP);
		define('FETCH_FUNC', \PDO::FETCH_FUNC);
	}

	protected function mysql() {
		$this->connection = NSY_DB::connect_mysql();
		return $this;
	}

	protected function dblib() {
		$this->connection = NSY_DB::connect_dblib();
		return $this;
	}

	protected function sqlsrv() {
		$this->connection = NSY_DB::connect_sqlsrv();
		return $this;
	}

	protected function query($query = "") {
		$this->query = $query;
		return $this;
	}

	protected function var($variables = "") {
		$this->variables = $variables;
		return $this;
	}

	protected function fetch_style($fetch_style = FETCH_BOTH) {
		$this->fetch_style = $fetch_style;
		return $this;
	}

	protected function bind($bind = "") {
		$this->bind = $bind;
		return $this;
	}

	protected function type($type = "") {
		$this->type = $type;
		return $this;
	}

	protected function column($column = 0) {
		$this->column = $column;
		return $this;
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

		// Close the statement & connection
		$stmt = null;
		$db = null;
    }

	/*
	Helper for PDO Multi Execute
	 */
	protected function multi_exec() {
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

		// Close the statement & connection
		$stmt = null;
		$db = null;
    }

	/*
	Start method for variables sequence
	 */
	protected function bind_name($bind_name = "") {
		$this->bind_name = $bind_name;
		return $this;
	}

	protected function attr($attr = "") {
		$this->attr = $attr;
		return $this;
	}

	protected function param($param = "") {
		$this->param = $param;
		return $this;
	}

	// Helper for NSY_Model to create a sequence of the named placeholders
	protected function sequence() {
		$in = "";
		foreach ($this->variables as $i => $item)
		{
		    $key = "$this->bind_name".$i;
		    $in .= "$key,";
		    $in_params[$key] = $item; // collecting values into key-value array
		}
		$in = rtrim($in,","); // :id0,:id1,:id2

		return [$in, $in_params];
	}
	/*
	End method for variables sequence
	 */

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
	protected function begin() {
		$this->connection->beginTransaction();
    }

	/*
	Helper for PDO Commit Transaction
	 */
	protected function commit() {
		$this->connection->commit();
    }

	/*
	Helper for PDO Rollback Transaction
	 */
	protected function rollback() {
		$this->connection->rollback();
    }

	/*
    Secure Input Element
     */
    protected function secure_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    /*
    Secure Form
     */
    protected function secure_form($form) {
        foreach ($form as $key => $value) {
            $form[$key] = $this->secure_input($value);
        }
    }

	/*
    Redirect URL
     */
    protected function redirect($url = NULL) {
		header("location:". BASE_URL . $url);
    }

	/*
	Fetching to json format
	 */
	protected function fetch_json($data = "") {
		$json_data = $data;
		$json_result = json_encode($json_data);

		return $json_result;
    }

}
