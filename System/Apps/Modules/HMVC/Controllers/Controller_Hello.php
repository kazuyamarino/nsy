<?php

declare(strict_types=1);

namespace System\Apps\Modules\HMVC\Controllers;

use System\Core\Load;
use Carbon\Carbon;
use System\Apps\General\Models\Model_Welcome;
use System\Apps\Modules\HMVC\Models\Model_Hello;

class Controller_Hello extends Load
{
	/**
	 * Render the HMVC demo page.
	 *
	 * @return void
	 */
	public function hello(): void
	{
		$arr = [
			'welcomeText' => Load::model(Model_Welcome::class)->welcomeText(), // Header greeting (Model_Welcome)
			'hmvcText' => Load::model(Model_Hello::class)->hmvcText(),         // HMVC headline (Model_Hello)
			'date' => Carbon::now(),                                           // Today's date (Carbon)
		];

		Load::template('Header', $arr);            // Apps/Templates/Header.php
		Load::view('HMVC', 'Index_Hello', $arr);   // Apps/Modules/HMVC/Views/Index_Hello.php
		Load::template('Footer', $arr);            // Apps/Templates/Footer.php
	}
}
