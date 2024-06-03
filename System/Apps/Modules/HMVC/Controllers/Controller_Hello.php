<?php

namespace System\Apps\Modules\HMVC\Controllers;

use System\Core\Load;
use Carbon\Carbon;
use System\Apps\General\Models\Model_Welcome;
use System\Apps\Modules\HMVC\Models\Model_Hello;

class Controller_Hello extends Load
{

	public function hello()
	{
		$arr = [
			'welcome_text' => Load::model(Model_Welcome::class)->welcome_text(), // Call the welcome_text method from Model_Welcome
			'hmvc_text' => Load::model(Model_Hello::class)->hmvc_text(), // Call the hmvc_text method from Model_Hello inside the HMVC module
			'date' => Carbon::now() // Instantiate today's date with Carbon
		];

		// Load HMVC view page
		Load::template('Header', $arr); // Header page, Apps/Templates/Header.php
		Load::view('HMVC', 'Index_Hello', $arr); // Index page, Apps/General/Views/Index_Hello.php
		Load::template('Footer', $arr); // Footer page, Apps/Templates/Footer.php
	}
}
