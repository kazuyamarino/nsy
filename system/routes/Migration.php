<?php
namespace System\Routes;

use System\Core\NSY_Router as Route;
use System\Core\NSY_Desk;

Class Migration
{

	public function __construct()
	{
		// Migration Route
		Route::any(
			'/migup=(:any)', function ($class) {
				NSY_Desk::mig_up($class);
			}
		);

		Route::any(
			'/migdown=(:any)', function ($class) {
				NSY_Desk::mig_down($class);
			}
		);
	}

}
