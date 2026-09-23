<?php

namespace System\Apps\General\Models;

use System\Core\DB;

class cm_mdl extends DB
{
	public function all(): array
	{
		return self::connect()->query('SELECT * FROM your_table')->fetch_all();
	}
}
