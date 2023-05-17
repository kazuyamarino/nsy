<?php

namespace System\Modules\HMVC\Controllers;

use System\Core\Load;
use Carbon\Carbon;
use System\Models\Model_Welcome;
use System\Modules\HMVC\Models\Model_Hello;

class Hello extends Load
{

	public function index_hmvc()
	{
		$arr = [
			'welcome' => Load::model(Model_Welcome::class)->welcome(), // Call the my_name method from Model_Welcome
			'hmvc_page' => Load::model(Model_Hello::class)->hmvc_page(), // Call the mvc_page method from Model_Hello inside the HMVC module
			'date' => Carbon::now() // Instantiate today's date with Carbon
		];

		// Load HMVC view page
		Load::template('Header', $arr);
		Load::view('HMVC', 'Index', $arr);
		Load::template('Footer', $arr);
	}
}
