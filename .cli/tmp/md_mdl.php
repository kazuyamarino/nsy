<?php

namespace System\Apps\Modules\ModuleName\Models;

use System\Core\DB;

class md_mdl extends DB
{
	public function all(): array
	{
		return self::connect()->query('SELECT * FROM your_table')->fetch_all();
	}
}
