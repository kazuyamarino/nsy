<?php
namespace System\Core;

use Optimus\Onion\Onion;

/**
* @method static Macaw get(string $route, Callable $callback)
* @method static Macaw post(string $route, Callable $callback)
* @method static Macaw put(string $route, Callable $callback)
* @method static Macaw delete(string $route, Callable $callback)
* @method static Macaw options(string $route, Callable $callback)
* @method static Macaw head(string $route, Callable $callback)
* NoahBuscher/Macaw https://github.com/noahbuscher/macaw/blob/master/Macaw.php
*/
class NSY_Router {
	public static $base	= '';
    public static $halts = false;
	public static $response = null;
    public static $routes = array();
    public static $methods = array();
    public static $callbacks = array();
    public static $maps = array();
    public static $patterns = array(
		':all'		 => '.*',
		':any'		 => '[^/]+',
		':slug'		 => '[a-z0-9-]+',
		':uslug'	 => '[\w-]+',			// slug + underscores
		':num'		 => '[0-9]+',
		':alpha'	 => '[A-Za-z]+',
		':alnum'	 => '[0-9A-Za-z]+',
		':date'      => '[0-9]{4}-[0-9]{2}-[0-9]{2}'
    );
    public static $error_callback;

    /**
    * Defines a route w/ callback and method
    */
    public static function __callstatic($method, $params) {
		if ( $method == 'group' ) {
			$params[0] = null;
		} else {
			$params[0] = '/' . config_app('app_dir') . self::$base . $params[0];
		}

        if ($method == 'map') {
            $maps = array_map('strtoupper', $params[0]);
            $uri = strpos($params[1], '/') === 0 ? $params[1] : '/' . $params[1];
            $callback = $params[2];
        } else {
            $maps = null;
            $uri = strpos($params[0], '/') === 0 ? $params[0] : '/' . $params[0];
            $callback = $params[1];
        }

        array_push(self::$maps, $maps);
        array_push(self::$routes, $uri);
        array_push(self::$methods, strtoupper($method));
        array_push(self::$callbacks, $callback);
    }

	/**
	 * NSY Middleware System
	 * @param  array $middleware
	 * @return void
	 */
	public static function middleware($middleware)
    {
		if ( is_filled($middleware) ) {
			$object = true;
			$onion = new Onion;

			self::$response = $onion->layer($middleware)->peel($object, function($object){
				return $object;
			});
		} else {
			$var_msg = 'The variable in the <mark>middleware()</mark> is improper or not an array';
			NSY_Desk::static_error_handler($var_msg);
			exit();
		}

		return new self;
	}

	/**
	 * goto() method for Instantiate controller
	 * Modified by Vikry Yuansah for NSY Routing System
	 * @param  mixed $controllerWithMethod
	 * @param  array  $vars
	 * @return void
	 */
	public static function goto($controllerWithMethod = null, $vars = array())
    {
		$params = explode('@', $controllerWithMethod);

		$module = explode('\\', $params[0]);
		if (count($module) > 1) {
			$fullclass = 'System\Modules\\'.$module[0].'\Controllers\\'.$module[1];
		} else {
			$fullclass = 'System\Controllers\\'.$module[0];
		}

		if ( is_filled($vars) ) {
			$defClass = new $fullclass;
			return $defClass->{$params[1]}($vars);
		} else {
			$defClass = new $fullclass;
			return $defClass->{$params[1]}();
		}
    }

	/**
	 * for() method for Instantiate controller from middleware
	 * Modified by Vikry Yuansah for NSY Routing System
	 * @param  mixed $controllerWithMethod
	 * @param  array  $vars
	 * @return void
	 */
	public function for($controllerWithMethod = null, $vars = array())
    {
		$params = explode('@', $controllerWithMethod);

		$module = explode('\\', $params[0]);
		if (count($module) > 1) {
			$fullclass = 'System\Modules\\'.$module[0].'\Controllers\\'.$module[1];
		} else {
			$fullclass = 'System\Controllers\\'.$module[0];
		}

		if ( is_filled($vars) ) {
			$defClass = new $fullclass;
			return $defClass->{$params[1]}($vars);
		} else {
			$defClass = new $fullclass;
			return $defClass->{$params[1]}();
		}
    }

	/**
	 * group() method for grouping route according to the base route first.
	 * Modified by Vikry Yuansah for NSY Routing System
	 * @param  string $base
	 * @param  array  $callback
	 * @return void
	 */
	public static function group($base, $callback) {
		$meths = 'group';

		self::$base	= $base;

		self::__callstatic($meths, [$base, $callback()]);

		self::$base = null;
	}

    /**
    * Defines callback if route is not found
    */
    public static function error($callback) {
        self::$error_callback = $callback;
    }

    public static function haltOnMatch($flag = true) {
        self::$halts = $flag;
    }

    /**
    * Runs the callback for the given request
    */
    public static function dispatch(){
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        $searches = array_keys(static::$patterns);
        $replaces = array_values(static::$patterns);

        $found_route = false;

        self::$routes = preg_replace('/\/+/', '/', self::$routes);

        // Check if route is defined without regex
        if (in_array($uri, self::$routes)) {
            $route_pos = array_keys(self::$routes, $uri);
            foreach ($route_pos as $route) {

                // Using an ANY option to match both GET and POST requests
                if (self::$methods[$route] == $method || self::$methods[$route] == 'ANY' || in_array($method, self::$maps[$route])) {
                    $found_route = true;

                    // If route is not an object
                    if (!is_object(self::$callbacks[$route])) {

                        // Grab all parts based on a / separator
                        $parts = explode('/',self::$callbacks[$route]);

                        // Collect the last index of the array
                        $last = end($parts);

                        // Grab the controller name and method call
                        $segments = explode('@',$last);

						$module = explode('\\', $segments[0]);

						if (count($module) > 1) {
							$fullclass = 'System\Modules\\'.$module[0].'\Controllers\\'.$module[1];
						} else {
							$fullclass = 'System\Controllers\\'.$module[0];
						}

                        // Instanitate controller
                        $controller = new $fullclass;

                        // Call method
                        $controller->{$segments[1]}();

                        if (self::$halts) return;
                    } else {
                        // Call closure
                        call_user_func(self::$callbacks[$route]);

                        if (self::$halts) return;
                    }
                }
            }
        } else {
            // Check if defined with regex
            $pos = 0;
            foreach (self::$routes as $route) {
                if (strpos($route, ':') !== false) {
                    $route = str_replace($searches, $replaces, $route);
                }

                if (preg_match('#^' . $route . '$#', $uri, $matched)) {
                    if (self::$methods[$pos] == $method || self::$methods[$pos] == 'ANY' || (!empty(self::$maps[$pos]) && in_array($method, self::$maps[$pos]))) {
                        $found_route = true;

                        // Remove $matched[0] as [1] is the first parameter.
                        array_shift($matched);

                        if (!is_object(self::$callbacks[$pos])) {

                            // Grab all parts based on a / separator
                            $parts = explode('/',self::$callbacks[$pos]);

                            // Collect the last index of the array
                            $last = end($parts);

                            // Grab the controller name and method call
                            $segments = explode('@',$last);

                            // Instanitate controller
                            $controller = new $segments[0]();

                            // Fix multi parameters
                            if (!method_exists($controller, $segments[1])) {
                                echo "controller and action not found";
                            } else {
                                call_user_func_array(array($controller, $segments[1]), $matched);
                            }

                            if (self::$halts) return;
                        } else {
                            call_user_func_array(self::$callbacks[$pos], $matched);

                            if (self::$halts) return;
                        }
                    }
                }
                $pos++;
            }
        }

        // Run the error callback if the route was not found
        if ($found_route == false) {
            if (!self::$error_callback) {
                self::$error_callback = function() {
                    header($_SERVER['SERVER_PROTOCOL']." 404 Not Found");
					exit('404 Not Found.');
                };
            } else {
                if (is_string(self::$error_callback)) {
                    self::get($_SERVER['REQUEST_URI'], self::$error_callback);
                    self::$error_callback = null;
                    self::dispatch();
                    return ;
                }
            }
            call_user_func(self::$error_callback);
        }
    }
}
