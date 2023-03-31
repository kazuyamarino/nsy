<?php

namespace System\Modules\Homepage\Controllers;

use System\Core\Load;

use Carbon\Carbon;

class Hello extends Load
{

	public function index_hmvc()
	{
		$arr = [
			'welcome' => Load::model('Model_Welcome')->welcome(), // Call the my_name method from Model_Welcome
			'hmvc_page' => Load::model('Homepage\Model_Hello')->hmvc_page(), // Call the mvc_page method from Model_Hello inside the Homepage module
			'date' => Carbon::now() // Instantiate today's date with Carbon
		];

		// Load HMVC view page
		Load::template('Header', $arr);
		Load::view('Homepage', 'Index', $arr);
		Load::template('Footer', $arr);
	}
}
