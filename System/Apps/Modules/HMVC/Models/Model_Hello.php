<?php

namespace System\Apps\Modules\HMVC\Models;

use System\Core\DB;

class Model_Hello extends DB
{

	public function hmvc_page()
	{
		return 'This is HMVC page';
	}
}
