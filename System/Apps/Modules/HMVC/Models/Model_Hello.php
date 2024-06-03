<?php

namespace System\Apps\Modules\HMVC\Models;

use System\Core\DB;

class Model_Hello extends DB
{

	public function hmvc_text()
	{
		// This line of code is executed for the function or method is returning the string 'This is HMVC page' on the Index_Hello.php page.
		return 'This is HMVC page';
	}
}
