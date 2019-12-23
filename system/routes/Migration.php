<?php
namespace Routes;

defined('ROOT') OR exit('No direct script access allowed');

use Core\NSY_Router as route;
use Core\NSY_Desk;

Class Migration extends NSY_Desk
{

	public function __construct()
	{
		/**
		 * Instantiate NSY Desk
		 */
		$this->desk = new NSY_Desk;

		// define Migration routes, the params format is :
		// Format = route::type('url', 'namespace\class_controller@method')

		// Migration Route
		route::any('migup=(:any)', function($class) {
			$this->desk->mig_up($class);
		});

		route::any('migdown=(:any)', function($class) {
			$this->desk->mig_down($class);
		});
	}

}
