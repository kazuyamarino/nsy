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

	/**
	 * Default Connection
	 *
	 * @param string $conn_name
	 * @return object
	 */
	protected static function connect(string $conn_name = 'primary')
	{
		switch (config_db($conn_name, 'DB_CONNECTION')) {
			case 'mysql':
				static::$connection = NSY_DB::connect_mysql($conn_name);
				return new static;
			case 'dblib':
				static::$connection = NSY_DB::connect_dblib($conn_name);
				return new static;
			case 'sqlsrv':
				static::$connection = NSY_DB::connect_sqlsrv($conn_name);
				return new static;
			case 'pgsql':
				static::$connection = NSY_DB::connect_pgsql($conn_name);
				return new static;
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
	 * @return void
	 */
	protected static function query(string $query = '')
	{
		if (is_filled($query)) {
			static::$query = $query;
		} else {
			$var_msg = "The value of query in the <mark>query(<strong>value</strong>)</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
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
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		return new static;
	}

	/**
	 * Function as a fetch style declaration
	 *
	 * @param  string $fetch_style
	 * @return void
	 */
	protected function style(string $fetch_style = FETCH_BOTH)
	{
		if (is_filled($fetch_style)) {
			static::$fetch_style = $fetch_style;
		} else {
			$var_msg = "The value of style in the <mark>style(<strong>value</strong>)</mark> is empty or undefined";
			NSY_Desk::static_error_handler($var_msg);
			exit();
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
	 * @return void
	 */
	protected function fetch_all()
	{
		// Check if there's connection defined on the models
		if (not_filled(static::$connection)) {
			echo '<pre>No Connection, Please check your connection again!</pre>';
			exit();
		} else {
			$stmt = static::$connection->prepare(static::$query);
			// if vars null, execute queries without vars, else execute it with defined on the models
			if (not_filled(static::$variables)) {
				$executed = $stmt->execute();
			} else {
				if (not_filled(static::$bind)) {
					$executed = $stmt->execute(static::$variables);
				} else {
					if (static::$bind == 'BINDVALUE') {
						if (is_array(static::$variables) || is_object(static::$variables)) {
							foreach (static::$variables as $key => &$res) {
								if (not_filled($res[1]) || not_filled($res[0])) {
									$var_msg = 'BindValue parameter type undefined, for example use PAR_INT or PAR_STR in the <strong>null</strong> variable.<br><br>[' . $key . ' => [' . $res[0] . ', <strong>null</strong>] ]';
									NSY_Desk::static_error_handler($var_msg);
								} else {
									$stmt->bindValue($key, $res[0], $res[1]);
								}
							}

							$executed = $stmt->execute();
						}
					} elseif (self::$bind == 'BINDPARAM') {
						if (is_array(static::$variables) || is_object(static::$variables)) {
							foreach (static::$variables as $key => &$res) {
								if (not_filled($res[1]) || not_filled($res[0])) {
									$var_msg = 'BindParam parameter type undefined, for example use PAR_INT or PAR_STR in the <strong>null</strong> variable.<br><br>[' . $key . ' => [' . $res[0] . ', <strong>null</strong>] ]';
									NSY_Desk::static_error_handler($var_msg);
								} else {
									$stmt->bindParam($key, $res[0], $res[1]);
								}
							}

							$executed = $stmt->execute();
						}
					} else {
						$var_msg = "The value that binds in the <mark>bind(<strong>value</strong>)</mark> is empty, undefined, or unknown parameter";
						NSY_Desk::static_error_handler($var_msg);
						exit();
					}
				}
			}

			// Check the errors, if no errors then return the results
			if ($executed || $stmt->errorCode() == 0) {
				$show_result = $stmt->fetchAll(static::$fetch_style ?? '');

				return $show_result;
			} else {
				$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
				NSY_Desk::static_error_handler($var_msg);
			}
		}

		// Close the statement & connection
		$stmt = null;
		static::$connection = null;
	}

	/**
	 * Helper for PDO Fetch
	 *
	 * @return void
	 */
	protected function fetch()
	{
		// Check if there's connection defined on the models
		if (not_filled(static::$connection)) {
			echo '<pre>No Connection, Please check your connection again!</pre>';
			exit();
		} else {
			$stmt = static::$connection->prepare(static::$query);
			// if vars null, execute queries without vars, else execute it with defined on the models
			if (not_filled(static::$variables)) {
				$executed = $stmt->execute();
			} else {
				if (not_filled(static::$bind)) {
					$executed = $stmt->execute(static::$variables);
				} else {
					if (static::$bind == 'BINDVALUE') {
						if (is_array(static::$variables) || is_object(static::$variables)) {
							foreach (static::$variables as $key => &$res) {
								if (not_filled($res[1]) || not_filled($res[0])) {
									$var_msg = 'BindValue parameter type undefined, for example use PAR_INT or PAR_STR in the <strong>null</strong> variable.<br><br>[' . $key . ' => [' . $res[0] . ', <strong>null</strong>] ]';
									NSY_Desk::static_error_handler($var_msg);
								} else {
									$stmt->bindValue($key, $res[0], $res[1]);
								}
							}

							$executed = $stmt->execute();
						}
					} elseif (self::$bind == 'BINDPARAM') {
						if (is_array(static::$variables) || is_object(static::$variables)) {
							foreach (static::$variables as $key => &$res) {
								if (not_filled($res[1]) || not_filled($res[0])) {
									$var_msg = 'BindParam parameter type undefined, for example use PAR_INT or PAR_STR in the <strong>null</strong> variable.<br><br>[' . $key . ' => [' . $res[0] . ', <strong>null</strong>] ]';
									NSY_Desk::static_error_handler($var_msg);
								} else {
									$stmt->bindParam($key, $res[0], $res[1]);
								}
							}

							$executed = $stmt->execute();
						}
					} else {
						$var_msg = "The value that binds in the <mark>bind(<strong>value</strong>)</mark> is empty, undefined, or unknown parameter";
						NSY_Desk::static_error_handler($var_msg);
						exit();
					}
				}
			}

			// Check the errors, if no errors then return the results
			if ($executed || $stmt->errorCode() == 0) {
				$show_result = $stmt->fetch(static::$fetch_style ?? '');

				return $show_result;
			} else {
				$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
				NSY_Desk::static_error_handler($var_msg);
			}
		}

		// Close the statement & connection
		$stmt = null;
		static::$connection = null;
	}

	/**
	 * Helper for PDO FetchColumn
	 *
	 * @param  int $column
	 * @return void
	 */
	protected function fetch_column(int $column = 0)
	{
		// Check if there's connection defined on the models
		if (not_filled(static::$connection)) {
			echo '<pre>No Connection, Please check your connection again!</pre>';
			exit();
		} else {
			$stmt = static::$connection->prepare(static::$query);
			// if vars null, execute queries without vars, else execute it with defined on the models
			if (not_filled(static::$variables)) {
				$executed = $stmt->execute();
			} else {
				if (not_filled(static::$bind)) {
					$executed = $stmt->execute(static::$variables);
				} else {
					if (static::$bind == 'BINDVALUE') {
						if (is_array(static::$variables) || is_object(static::$variables)) {
							foreach (static::$variables as $key => &$res) {
								if (not_filled($res[1]) || not_filled($res[0])) {
									$var_msg = 'BindValue parameter type undefined, for example use PAR_INT or PAR_STR in the <strong>null</strong> variable.<br><br>[' . $key . ' => [' . $res[0] . ', <strong>null</strong>] ]';
									NSY_Desk::static_error_handler($var_msg);
								} else {
									$stmt->bindValue($key, $res[0], $res[1]);
								}
							}

							$executed = $stmt->execute();
						}
					} elseif (self::$bind == 'BINDPARAM') {
						if (is_array(static::$variables) || is_object(static::$variables)) {
							foreach (static::$variables as $key => &$res) {
								if (not_filled($res[1]) || not_filled($res[0])) {
									$var_msg = 'BindParam parameter type undefined, for example use PAR_INT or PAR_STR in the <strong>null</strong> variable.<br><br>[' . $key . ' => [' . $res[0] . ', <strong>null</strong>] ]';
									NSY_Desk::static_error_handler($var_msg);
								} else {
									$stmt->bindParam($key, $res[0], $res[1]);
								}
							}

							$executed = $stmt->execute();
						}
					} else {
						$var_msg = "The value that binds in the <mark>bind(<strong>value</strong>)</mark> is empty, undefined, or unknown parameter";
						NSY_Desk::static_error_handler($var_msg);
						exit();
					}
				}
			}

			// Check the errors, if no errors then return the results
			if ($executed || $stmt->errorCode() == 0) {
				$show_result = $stmt->fetchColumn($column ?? 0);

				return $show_result;
			} else {
				$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
				NSY_Desk::static_error_handler($var_msg);
			}
		}

		// Close the statement & connection
		$stmt = null;
		static::$connection = null;
	}

	/**
	 * Helper for PDO RowCount
	 *
	 * @return void
	 */
	protected function row_count()
	{
		// Check if there's connection defined on the models
		if (not_filled(static::$connection)) {
			echo '<pre>No Connection, Please check your connection again!</pre>';
			exit();
		} else {
			$stmt = static::$connection->prepare(static::$query);
			// if vars null, execute queries without vars, else execute it with defined on the models
			if (not_filled(static::$variables)) {
				$executed = $stmt->execute();
			} else {
				if (not_filled(static::$bind)) {
					$executed = $stmt->execute(static::$variables);
				} else {
					if (static::$bind == 'BINDVALUE') {
						if (is_array(static::$variables) || is_object(static::$variables)) {
							foreach (static::$variables as $key => &$res) {
								if (not_filled($res[1]) || not_filled($res[0])) {
									$var_msg = 'BindValue parameter type undefined, for example use PAR_INT or PAR_STR in the <strong>null</strong> variable.<br><br>[' . $key . ' => [' . $res[0] . ', <strong>null</strong>] ]';
									NSY_Desk::static_error_handler($var_msg);
								} else {
									$stmt->bindValue($key, $res[0], $res[1]);
								}
							}

							$executed = $stmt->execute();
						}
					} elseif (self::$bind == 'BINDPARAM') {
						if (is_array(static::$variables) || is_object(static::$variables)) {
							foreach (static::$variables as $key => &$res) {
								if (not_filled($res[1]) || not_filled($res[0])) {
									$var_msg = 'BindParam parameter type undefined, for example use PAR_INT or PAR_STR in the <strong>null</strong> variable.<br><br>[' . $key . ' => [' . $res[0] . ', <strong>null</strong>] ]';
									NSY_Desk::static_error_handler($var_msg);
								} else {
									$stmt->bindParam($key, $res[0], $res[1]);
								}
							}

							$executed = $stmt->execute();
						}
					} else {
						$var_msg = "The value that binds in the <mark>bind(<strong>value</strong>)</mark> is empty, undefined, or unknown parameter";
						NSY_Desk::static_error_handler($var_msg);
						exit();
					}
				}
			}

			// Check the errors, if no errors then return the results
			if ($executed || $stmt->errorCode() == 0) {
				$show_result = $stmt->rowCount();

				return $show_result;
			} else {
				$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
				NSY_Desk::static_error_handler($var_msg);
			}
		}

		// Close the statement & connection
		$stmt = null;
		static::$connection = null;
	}

	/**
	 * Helper for PDO Execute
	 *
	 * @return void
	 */
	protected function exec()
	{
		if (config_app('csrf_token') === 'true') {
			try {
				// Run CSRF check, on POST data, in exception mode, for 10 minutes, in one-time mode.
				csrf_check('csrf_token', $_POST, true, 60 * 10, false);

				// Check if there's connection defined on the models
				if (not_filled(static::$connection)) {
					echo '<pre>No Connection, Please check your connection again!</pre>';
					exit();
				} else {
					// if vars null, execute queries without vars, else execute it with defined on the models
					if (not_filled(static::$variables)) {
						if (config_app('transaction') === 'on') {
							try {
								// begin the transaction
								static::$connection->beginTransaction();

								$stmt = static::$connection->prepare(static::$query);
								$executed = $stmt->execute();

								// commit the transaction
								static::$connection->commit();
							} catch (\PDOException $e) {
								// rollback the transaction
								static::$connection->rollBack();

								// show the error message
								die($e->getMessage());
							}
						} elseif (config_app('transaction') === 'off') {
							$stmt = static::$connection->prepare(static::$query);
							$executed = $stmt->execute();
						} else {
							echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
							exit();
						}
					} else {
						if (not_filled(static::$bind)) {
							if (config_app('transaction') === 'on') {
								try {
									// begin the transaction
									static::$connection->beginTransaction();

									$stmt = static::$connection->prepare(static::$query);
									$executed = $stmt->execute(static::$variables);

									// commit the transaction
									static::$connection->commit();
								} catch (\PDOException $e) {
									// rollback the transaction
									static::$connection->rollBack();

									// show the error message
									die($e->getMessage());
								}
							} elseif (config_app('transaction') === 'off') {
								$stmt = static::$connection->prepare(static::$query);
								$executed = $stmt->execute(static::$variables);
							} else {
								echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
								exit();
							}
						} else {
							if (static::$bind == 'BINDVALUE') {
								if (config_app('transaction') === 'on') {
									try {
										// begin the transaction
										static::$connection->beginTransaction();

										$stmt = static::$connection->prepare(static::$query);
										if (is_array(static::$variables) || is_object(static::$variables)) {
											foreach (static::$variables as $key => &$res) {
												if (not_filled($res[1]) || not_filled($res[0])) {
													$var_msg = 'BindValue parameter type undefined, for example use PAR_INT or PAR_STR in the <strong>null</strong> variable.<br><br>[' . $key . ' => [' . $res[0] . ', <strong>null</strong>] ]';
													NSY_Desk::static_error_handler($var_msg);
												} else {
													$stmt->bindValue($key, $res[0], $res[1]);
												}
											}

											$executed = $stmt->execute();
										}

										// commit the transaction
										static::$connection->commit();
									} catch (\PDOException $e) {
										// rollback the transaction
										static::$connection->rollBack();

										// show the error message
										die($e->getMessage());
									}
								} elseif (config_app('transaction') === 'off') {
									$stmt = static::$connection->prepare(static::$query);
									if (is_array(static::$variables) || is_object(static::$variables)) {
										foreach (static::$variables as $key => &$res) {
											if (not_filled($res[1]) || not_filled($res[0])) {
												$var_msg = 'BindValue parameter type undefined, for example use PAR_INT or PAR_STR in the <strong>null</strong> variable.<br><br>[' . $key . ' => [' . $res[0] . ', <strong>null</strong>] ]';
												NSY_Desk::static_error_handler($var_msg);
											} else {
												$stmt->bindValue($key, $res[0], $res[1]);
											}
										}

										$executed = $stmt->execute();
									}
								} else {
									echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
									exit();
								}
							} elseif (self::$bind == 'BINDPARAM') {
								if (config_app('transaction') === 'on') {
									try {
										// begin the transaction
										static::$connection->beginTransaction();

										$stmt = static::$connection->prepare(static::$query);
										if (is_array(static::$variables) || is_object(static::$variables)) {
											foreach (static::$variables as $key => &$res) {
												if (not_filled($res[1]) || not_filled($res[0])) {
													$var_msg = 'BindParam parameter type undefined, for example use PAR_INT or PAR_STR in the <strong>null</strong> variable.<br><br>[' . $key . ' => [' . $res[0] . ', <strong>null</strong>] ]';
													NSY_Desk::static_error_handler($var_msg);
												} else {
													$stmt->bindParam($key, $res[0], $res[1]);
												}
											}

											$executed = $stmt->execute();
										}

										// commit the transaction
										static::$connection->commit();
									} catch (\PDOException $e) {
										// rollback the transaction
										static::$connection->rollBack();

										// show the error message
										die($e->getMessage());
									}
								} elseif (config_app('transaction') === 'off') {
									$stmt = static::$connection->prepare(static::$query);
									if (is_array(static::$variables) || is_object(static::$variables)) {
										foreach (static::$variables as $key => &$res) {
											if (not_filled($res[1]) || not_filled($res[0])) {
												$var_msg = 'BindParam parameter type undefined, for example use PAR_INT or PAR_STR in the <strong>null</strong> variable.<br><br>[' . $key . ' => [' . $res[0] . ', <strong>null</strong>] ]';
												NSY_Desk::static_error_handler($var_msg);
											} else {
												$stmt->bindParam($key, $res[0], $res[1]);
											}
										}

										$executed = $stmt->execute();
									}
								} else {
									echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
									exit();
								}
							} else {
								$var_msg = "The value that binds in the <mark>bind(<strong>value</strong>)</mark> is empty, undefined, or unknown parameter";
								NSY_Desk::static_error_handler($var_msg);
								exit();
							}
						}
					}

					// Check the errors, if no errors then return the results
					if ($executed || $stmt->errorCode() == 0) {
						return true;
					} else {
						if (not_filled(static::$variables)) {
							$var_msg = "Syntax error or access violation! \nNo parameter were bound for query, \nPlease check your query again!";
							NSY_Desk::static_error_handler($var_msg);
						} else {
							$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
							NSY_Desk::static_error_handler($var_msg);
						}
					}
				}

				$result = '<pre>CSRF check passed. Form parsed.</pre>'; // Just info
			} catch (\Exception $e) {
				// CSRF attack detected
				echo '<pre>' . $e->getMessage() . ' Form ignored.</pre>'; // Just info
				exit();
			}
		} elseif (config_app('csrf_token') === 'false') {
			// Check if there's connection defined on the models
			if (not_filled(static::$connection)) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				// if vars null, execute queries without vars, else execute it with defined on the models
				if (not_filled(static::$variables)) {
					if (config_app('transaction') === 'on') {
						try {
							// begin the transaction
							static::$connection->beginTransaction();

							$stmt = static::$connection->prepare(static::$query);
							$executed = $stmt->execute();

							// commit the transaction
							static::$connection->commit();
						} catch (\PDOException $e) {
							// rollback the transaction
							static::$connection->rollBack();

							// show the error message
							die($e->getMessage());
						}
					} elseif (config_app('transaction') === 'off') {
						$stmt = static::$connection->prepare(static::$query);
						$executed = $stmt->execute();
					} else {
						echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
						exit();
					}
				} else {
					if (not_filled(static::$bind)) {
						if (config_app('transaction') === 'on') {
							try {
								// begin the transaction
								static::$connection->beginTransaction();

								$stmt = static::$connection->prepare(static::$query);
								$executed = $stmt->execute(static::$variables);

								// commit the transaction
								static::$connection->commit();
							} catch (\PDOException $e) {
								// rollback the transaction
								static::$connection->rollBack();

								// show the error message
								die($e->getMessage());
							}
						} elseif (config_app('transaction') === 'off') {
							$stmt = static::$connection->prepare(static::$query);
							$executed = $stmt->execute(static::$variables);
						} else {
							echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
							exit();
						}
					} else {
						if (static::$bind == 'BINDVALUE') {
							if (config_app('transaction') === 'on') {
								try {
									// begin the transaction
									static::$connection->beginTransaction();

									$stmt = static::$connection->prepare(static::$query);
									if (is_array(static::$variables) || is_object(static::$variables)) {
										foreach (static::$variables as $key => &$res) {
											if (not_filled($res[1]) || not_filled($res[0])) {
												$var_msg = 'BindValue parameter type undefined, for example use PAR_INT or PAR_STR in the <strong>null</strong> variable.<br><br>[' . $key . ' => [' . $res[0] . ', <strong>null</strong>] ]';
												NSY_Desk::static_error_handler($var_msg);
											} else {
												$stmt->bindValue($key, $res[0], $res[1]);
											}
										}

										$executed = $stmt->execute();
									}

									// commit the transaction
									static::$connection->commit();
								} catch (\PDOException $e) {
									// rollback the transaction
									static::$connection->rollBack();

									// show the error message
									die($e->getMessage());
								}
							} elseif (config_app('transaction') === 'off') {
								$stmt = static::$connection->prepare(static::$query);
								if (is_array(static::$variables) || is_object(static::$variables)) {
									foreach (static::$variables as $key => &$res) {
										if (not_filled($res[1]) || not_filled($res[0])) {
											$var_msg = 'BindValue parameter type undefined, for example use PAR_INT or PAR_STR in the <strong>null</strong> variable.<br><br>[' . $key . ' => [' . $res[0] . ', <strong>null</strong>] ]';
											NSY_Desk::static_error_handler($var_msg);
										} else {
											$stmt->bindValue($key, $res[0], $res[1]);
										}
									}

									$executed = $stmt->execute();
								}
							} else {
								echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
								exit();
							}
						} elseif (self::$bind == 'BINDPARAM') {
							if (config_app('transaction') === 'on') {
								try {
									// begin the transaction
									static::$connection->beginTransaction();

									$stmt = static::$connection->prepare(static::$query);
									if (is_array(static::$variables) || is_object(static::$variables)) {
										foreach (static::$variables as $key => &$res) {
											if (not_filled($res[1]) || not_filled($res[0])) {
												$var_msg = 'BindParam parameter type undefined, for example use PAR_INT or PAR_STR in the <strong>null</strong> variable.<br><br>[' . $key . ' => [' . $res[0] . ', <strong>null</strong>] ]';
												NSY_Desk::static_error_handler($var_msg);
											} else {
												$stmt->bindParam($key, $res[0], $res[1]);
											}
										}

										$executed = $stmt->execute();
									}

									// commit the transaction
									static::$connection->commit();
								} catch (\PDOException $e) {
									// rollback the transaction
									static::$connection->rollBack();

									// show the error message
									die($e->getMessage());
								}
							} elseif (config_app('transaction') === 'off') {
								$stmt = static::$connection->prepare(static::$query);
								if (is_array(static::$variables) || is_object(static::$variables)) {
									foreach (static::$variables as $key => &$res) {
										if (not_filled($res[1]) || not_filled($res[0])) {
											$var_msg = 'BindParam parameter type undefined, for example use PAR_INT or PAR_STR in the <strong>null</strong> variable.<br><br>[' . $key . ' => [' . $res[0] . ', <strong>null</strong>] ]';
											NSY_Desk::static_error_handler($var_msg);
										} else {
											$stmt->bindParam($key, $res[0], $res[1]);
										}
									}

									$executed = $stmt->execute();
								}
							} else {
								echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
								exit();
							}
						} else {
							$var_msg = "The value that binds in the <mark>bind(<strong>value</strong>)</mark> is empty, undefined, or unknown parameter";
							NSY_Desk::static_error_handler($var_msg);
							exit();
						}
					}
				}

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					return true;
				} else {
					if (not_filled(static::$variables)) {
						$var_msg = "Syntax error or access violation! \nNo parameter were bound for query, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
					} else {
						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
					}
				}
			}
		} else {
			echo '<pre>The CSRF Token Protection is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		static::$connection = null;
	}

	/**
	 * Helper for PDO Multi Insert
	 *
	 * @return void
	 */
	protected function multi_insert()
	{
		if (config_app('csrf_token') === 'true') {

			try {
				// Run CSRF check, on POST data, in exception mode, for 10 minutes, in one-time mode.
				csrf_check('csrf_token', $_POST, true, 60 * 10, false);

				// Check if there's connection defined on the models
				if (not_filled(static::$connection)) {
					echo '<pre>No Connection, Please check your connection again!</pre>';
					exit();
				} else {
					$rows = count(static::$variables);
					$cols = count(static::$variables[0]);
					$rowString = '(' . rtrim(str_repeat('?,', $cols), ',') . '),';
					$valString = rtrim(str_repeat($rowString, $rows), ',');

					// if vars null, execute queries without vars, else execute it with defined on the models
					if (not_filled(static::$variables)) {
						$var_msg = "Syntax error or access violation! \nNo parameter were bound for query, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
					} else {
						if (config_app('transaction') === 'on') {
							try {
								// begin the transaction
								static::$connection->beginTransaction();

								$stmt = static::$connection->prepare(static::$query . ' VALUES ' . $valString);

								$bindArray = array();
								array_walk_recursive(
									static::$variables,
									function ($item) use (&$bindArray) {
										$bindArray[] = $item;
									}
								);
								$executed = $stmt->execute($bindArray);

								// commit the transaction
								static::$connection->commit();
							} catch (\PDOException $e) {
								// rollback the transaction
								static::$connection->rollBack();

								// show the error message
								die($e->getMessage());
							}
						} elseif (config_app('transaction') === 'off') {
							$stmt = static::$connection->prepare(static::$query . ' VALUES ' . $valString);

							$bindArray = array();
							array_walk_recursive(
								static::$variables,
								function ($item) use (&$bindArray) {
									$bindArray[] = $item;
								}
							);
							$executed = $stmt->execute($bindArray);
						} else {
							echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
							exit();
						}
					}

					// Check the errors, if no errors then return the results
					if ($executed || $stmt->errorCode() == 0) {
						return true;
					} else {
						if (not_filled(static::$variables)) {
							$var_msg = "Syntax error or access violation! \nNo parameter were bound for query, \nPlease check your query again!";
							NSY_Desk::static_error_handler($var_msg);
						} else {
							$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
							NSY_Desk::static_error_handler($var_msg);
						}
					}
				}
				$result = '<pre>CSRF check passed. Form parsed.</pre>'; // Just info
			} catch (\Exception $e) {
				// CSRF attack detected
				echo '<pre>' . $e->getMessage() . ' Form ignored.</pre>'; // Just info
				exit();
			}
		} elseif (config_app('csrf_token') === 'false') {

			// Check if there's connection defined on the models
			if (not_filled(static::$connection)) {
				echo '<pre>No Connection, Please check your connection again!</pre>';
				exit();
			} else {
				$rows = count(static::$variables);
				$cols = count(static::$variables[0]);
				$rowString = '(' . rtrim(str_repeat('?,', $cols), ',') . '),';
				$valString = rtrim(str_repeat($rowString, $rows), ',');

				// if vars null, execute queries without vars, else execute it with defined on the models
				if (not_filled(static::$variables)) {
					$var_msg = "Syntax error or access violation! \nNo parameter were bound for query, \nPlease check your query again!";
					NSY_Desk::static_error_handler($var_msg);
				} else {
					if (config_app('transaction') === 'on') {
						try {
							// begin the transaction
							static::$connection->beginTransaction();

							$stmt = static::$connection->prepare(static::$query . ' VALUES ' . $valString);

							$bindArray = array();
							array_walk_recursive(
								static::$variables,
								function ($item) use (&$bindArray) {
									$bindArray[] = $item;
								}
							);
							$executed = $stmt->execute($bindArray);

							// commit the transaction
							static::$connection->commit();
						} catch (\PDOException $e) {
							// rollback the transaction
							static::$connection->rollBack();

							// show the error message
							die($e->getMessage());
						}
					} elseif (config_app('transaction') === 'off') {
						$stmt = static::$connection->prepare(static::$query . ' VALUES ' . $valString);

						$bindArray = array();
						array_walk_recursive(
							static::$variables,
							function ($item) use (&$bindArray) {
								$bindArray[] = $item;
							}
						);
						$executed = $stmt->execute($bindArray);
					} else {
						echo '<pre>The Transaction Mode is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
						exit();
					}
				}

				// Check the errors, if no errors then return the results
				if ($executed || $stmt->errorCode() == 0) {
					return true;
				} else {
					if (not_filled(static::$variables)) {
						$var_msg = "Syntax error or access violation! \nNo parameter were bound for query, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
					} else {
						$var_msg = "Syntax error or access violation! \nYou have an error in your SQL syntax, \nPlease check your query again!";
						NSY_Desk::static_error_handler($var_msg);
					}
				}
			}
		} else {
			echo '<pre>The CSRF Token Protection is not set correctly. Please check in the <strong><i>System/Config/App.php</i></strong></pre>';
			exit();
		}

		// Close the statement & connection
		$stmt = null;
		static::$connection = null;
	}

	/**
	 * Helper for PDO setAttribute
	 *
	 * @param string $param
	 * @param string $value
	 * @return void
	 */
	protected function pdo_set_attr(string $param = '', string $value = '')
	{
		static::$connection->setAttribute($param, $value);
		return new static;
	}

	/**
	 * Helper for PDO setAttribute
	 *
	 * @param string $param
	 * @return void
	 */
	protected function pdo_get_attr(string $param = '')
	{
		static::$connection->getAttribute($param);
		return new static;
	}

	/**
	 * Helper for PDO Begin Transaction
	 *
	 * @return void
	 */
	protected function begin_trans()
	{
		static::$connection->beginTransaction();
		return new static;
	}
	/**
	 * Helper for PDO Commit Transaction
	 *
	 * @return void
	 */
	protected function end_trans()
	{
		static::$connection->commit();
		return new static;
	}
	/**
	 * Helper for PDO Rollback Transaction
	 *
	 * @return void
	 */
	protected function null_trans()
	{
		static::$connection->rollback();
		return new static;
	}
}
