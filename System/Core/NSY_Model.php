<?php

namespace System\Core;

use System\Core\NSY_DB;

defined('ROOT') OR exit('No direct script access allowed');

class NSY_Model {

	/*
	Helper for NSY_Model PDO variables
	 */
	protected function __construct() {
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

	/*
	Helper for PDO FetchAll
	 */
	protected function fetchAll($connection = "", $query = "", $vars = "", $mode = FETCH_BOTH, $bind = "", $type = "") {
		// Check if there's connection defined on the models
		if ($connection == "" || empty($connection) || !isset($connection)) {
			echo "No Connection Bro, Please check your code again!";
		} else {
			$stmt = $connection->prepare($query);
			// if vars null, execute queies without vars, else execute it with defined on the models
			if ($vars == "" || empty($vars) || !isset($vars)) {
				$executed = $stmt->execute();
				if (!$executed) {
					$errors = $stmt->errorInfo();
					echo "Error Query : ".($errors[0]);
					echo ", No parameter were bound for query, Please check your query again!";
					exit();
				}
			} else {
				if ($bind == "BINDVALUE") {
					if ($type == "" || empty($type) || !isset($type)) {
						echo "BindValue parameter type undefined, for example use PARAM_INT or PARAM_STR";
					} else {
						foreach ($vars as $key => $res) {
							$stmt->bindValue($key, $res, $type);
						}
						$stmt->execute();
					}
				} elseif ($bind == "BINDPARAM") {
					if ($type == "" || empty($type) || !isset($type)) {
						echo "BindParam parameter type undefined, for example use PARAM_INT or PARAM_STR";
					} else {
						foreach ($vars as $key => $res) {
							$stmt->bindParam($key, $res, $type);
						}
						$stmt->execute();
					}
				} elseif ($bind == "" || empty($bind) || !isset($bind)) {
					$stmt->execute($vars);
				}
			}

			// Check the errors, if no errors then return the results
			if ($stmt->errorCode() == 0) {
				$show_result = $stmt->fetchAll($mode);

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
	protected function fetch($connection = "", $query = "", $vars = "", $mode = FETCH_BOTH, $bind = "", $type = "") {
		// Check if there's connection defined on the models
		if ($connection == "" || empty($connection) || !isset($connection)) {
			echo "No Connection Bro, Please check your connection again!";
		} else {
			$stmt = $connection->prepare($query);
			// if vars null, execute queies without vars, else execute it with defined on the models
			if ($vars == "" || empty($vars) || !isset($vars)) {
				$executed = $stmt->execute();
				if (!$executed) {
					$errors = $stmt->errorInfo();
					echo "Error Query : ".($errors[0]);
					echo ", No parameter were bound for query, Please check your query again!";
					exit();
				}
			} else {
				if ($bind == "BINDVALUE") {
					if ($type == "" || empty($type) || !isset($type)) {
						echo "BindValue parameter type undefined, for example use PARAM_INT or PARAM_STR";
					} else {
						foreach ($vars as $key => $res) {
							$stmt->bindValue($key, $res, $type);
						}
						$stmt->execute();
					}
				} elseif ($bind == "BINDPARAM") {
					if ($type == "" || empty($type) || !isset($type)) {
						echo "BindParam parameter type undefined, for example use PARAM_INT or PARAM_STR";
					} else {
						foreach ($vars as $key => $res) {
							$stmt->bindParam($key, $res, $type);
						}
						$stmt->execute();
					}
				} elseif ($bind == "" || empty($bind) || !isset($bind)) {
					$stmt->execute($vars);
				}
			}

			// Check the errors, if no errors then return the results
			if ($stmt->errorCode() == 0) {
				$show_result = $stmt->fetch($mode);

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
	protected function fetchColumn($connection = "", $query = "", $vars = "", $column = 0, $bind = "", $type = "") {
		// Check if there's connection defined on the models
		if ($connection == "" || empty($connection) || !isset($connection)) {
			echo "No Connection Bro, Please check your connection again!";
		} else {
			$stmt = $connection->prepare($query);
			// if vars null, execute queies without vars, else execute it with defined on the models
			if ($vars == "" || empty($vars) || !isset($vars)) {
				$executed = $stmt->execute();
				if (!$executed) {
					$errors = $stmt->errorInfo();
					echo "Error Query : ".($errors[0]);
					echo ", No parameter were bound for query, Please check your query again!";
					exit();
				}
			} else {
				if ($bind == "BINDVALUE") {
					if ($type == "" || empty($type) || !isset($type)) {
						echo "BindValue parameter type undefined, for example use PARAM_INT or PARAM_STR";
					} else {
						foreach ($vars as $key => $res) {
							$stmt->bindValue($key, $res, $type);
						}
						$stmt->execute();
					}
				} elseif ($bind == "BINDPARAM") {
					if ($type == "" || empty($type) || !isset($type)) {
						echo "BindParam parameter type undefined, for example use PARAM_INT or PARAM_STR";
					} else {
						foreach ($vars as $key => $res) {
							$stmt->bindParam($key, $res, $type);
						}
						$stmt->execute();
					}
				} elseif ($bind == "" || empty($bind) || !isset($bind)) {
					$stmt->execute($vars);
				}
			}

			// Check the errors, if no errors then return the results
			if ($stmt->errorCode() == 0) {
				$show_result = $stmt->fetchColumn($column);

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
	protected function rowCount($connection = "", $query = "", $vars = "") {
		// Check if there's connection defined on the models
		if ($connection == "" || empty($connection) || !isset($connection)) {
			echo "No Connection Bro, Please check your connection again!";
		} else {
			$stmt = $connection->prepare($query);
			// if vars null, execute queies without vars, else execute it with defined on the models
			if ($vars == "" || empty($vars) || !isset($vars)) {
				$executed = $stmt->execute();
				if (!$executed) {
					$errors = $stmt->errorInfo();
					echo "Error Query : ".($errors[0]);
					echo ", No parameter were bound for query, Please check your query again!";
					exit();
				}
			} else {
				$stmt->execute($vars);
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
	protected function execute($connection = "", $query = "", $vars = "") {
		// Check if there's connection defined on the models
		if ($connection == "" || empty($connection) || !isset($connection)) {
			echo "No Connection Bro, Please check your connection again!";
		} else {
			$stmt = $connection->prepare($query);
			// if vars null, execute queies without vars, else execute it with defined on the models
			if ($vars == "" || empty($vars) || !isset($vars)) {
				$executed = $stmt->execute();
				if (!$executed) {
					$errors = $stmt->errorInfo();
					echo "Error Query : ".($errors[0]);
					echo ", No parameter were bound for query, Please check your query again!";
					exit();
				}
			} else {
				$executed = $stmt->execute($vars);
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
	protected function multiExecute($connection = "", $query = "", $vars = "") {
		// Check if there's connection defined on the models
		if ($connection == "" || empty($connection) || !isset($connection)) {
			echo "No Connection Bro, Please check your connection again!";
		} else {
			$stmt = $connection->prepare($query);
			// if vars null, execute queies without vars, else execute it with defined on the models
			if ($vars == "" || empty($vars) || !isset($vars)) {
				$executed = $stmt->execute();
				if (!$executed) {
					$errors = $stmt->errorInfo();
					echo "Error Query : ".($errors[0]);
					echo ", No parameter were bound for query, Please check your query again!";
					exit();
				}
			} else {
				foreach($vars as $key => $params) {
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
	Helper for NSY_Model to create a sequence of the named placeholders
	 */
	protected function var_sequence($varname = "", $ids = "", $var = "", $param = "") {
		$in = "";
		foreach ($ids as $i => $item)
		{
		    $key = "$varname".$i;
		    $in .= "$key,";
		    $in_params[$key] = $item; // collecting values into key-value array
		}
		$in = rtrim($in,","); // :id0,:id1,:id2

		if ($var == "" || empty($var) || !isset($var) || $param == "" || empty($param) || !isset($param)) {
			return [$in, $in_params];
		} else {
			return [
				$var => $in,
				$param => $in_params
			];
		}
	}

	/*
	Helper for PDO Emulation False
	 */
	protected function emulatePreparesFalse($connection = "") {
		$connection->setAttribute(\PDO::ATTR_EMULATE_PREPARES, FALSE);
    }

	/*
	Helper for PDO MYSQL_ATTR_USE_BUFFERED_QUERY
	 */
	protected function useBufferQueryTrue($connection = "") {
		$connection->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, TRUE);
    }

	protected function useBufferQueryFalse($connection = "") {
		$connection->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, FALSE);
    }

	/*
	Helper for PDO ATTR_STRINGIFY_FETCHES
	 */
	protected function stringifyFetchesTrue($connection = "") {
		$connection->setAttribute(\PDO::ATTR_STRINGIFY_FETCHES, TRUE);
    }

	/*
	Helper for PDO Begin Transaction
	 */
	protected function begin($connection = "") {
		$connection->beginTransaction();
    }

	/*
	Helper for PDO Commit Transaction
	 */
	protected function commit($connection = "") {
		$connection->commit();
    }

	/*
	Helper for PDO Rollback Transaction
	 */
	protected function rollback($connection = "") {
		$connection->rollback();
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

}
