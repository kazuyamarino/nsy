<?php

namespace System\Controllers;

use System\Core\Load;

use Carbon\Carbon;

class Welcome extends Load
{

	public function index()
	{
		$arr = [
			'welcome' => Load::model('Model_Welcome')->welcome(), // Call the welcome method from Model_Welcome
			'mvc_page' => Load::model('Homepage\Model_Hello')->mvc_page(), // Call the mvc_page method from Model_Hello inside the Homepage module
			'date' => Carbon::now() // Instantiate today's date with Carbon
		];

		// Load MVC view page
		Load::template('Header', $arr);
		Load::view(null, 'Index', $arr);
		Load::template('Footer', $arr);
	}
}
