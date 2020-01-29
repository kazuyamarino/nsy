<?php
namespace System\Controllers;

use System\Core\Load;

use Carbon\Carbon;

class Welcome extends Load
{

	public function index()
	{
		$arr = [
			'welcome' => Load::model('Model_Welcome')->welcome(), // Call my_name method from Model_Welcome
			'mvc_page' => Load::model('Homepage\Model_Hello')->mvc_page(), // Call mvc_page method from Model_Hello
			'date' => Carbon::now() // Instantiate today date with Carbon
		];

		// Load MVC view page
		Load::template('header', $arr);
		Load::view(null, 'index', $arr);
		Load::template('footer', $arr);
	}

}
