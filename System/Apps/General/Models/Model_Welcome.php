<?php

namespace System\Apps\General\Models;

use System\Core\DB;

class Model_Welcome extends DB
{

	public function welcome_text()
	{
		// This line of code is executed for the function or method is returning the string 'Welcome to NSY PHP Framework' on the Header.php page.
		return 'Welcome to NSY PHP Framework';
	}

	public function mvc_text()
	{
		// This line of code is executed for the function or method is returning the string 'This is MVC page' on the Index_Welcome.php page.
		return 'This is MVC page';
	}
}
