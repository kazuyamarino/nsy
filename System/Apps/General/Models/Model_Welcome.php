<?php

namespace System\Apps\General\Models;

use System\Core\DB;

class Model_Welcome extends DB
{

	public function welcome()
	{
		return 'Welcome to NSY PHP Framework';
	}

	public function mvc_page()
	{
		return 'This is MVC page';
	}
}
