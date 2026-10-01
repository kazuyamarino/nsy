<?php

declare(strict_types=1);

namespace System\Apps\General\Models;

use System\Core\DB;

class Model_Welcome extends DB
{
	/**
	 * Header greeting, rendered by Apps/Templates/Header.php.
	 *
	 * @return string
	 */
	public function welcomeText(): string
	{
		return 'Welcome to NSY PHP Framework';
	}
}
