<?php

declare(strict_types=1);

namespace System\Apps\Modules\HMVC\Models;

use System\Core\DB;

class Model_Hello extends DB
{
	/**
	 * HMVC headline, rendered by Apps/Modules/HMVC/Views/Index_Hello.php.
	 *
	 * @return string
	 */
	public function hmvcText(): string
	{
		return 'This is HMVC page';
	}
}
