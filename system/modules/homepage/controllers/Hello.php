<?php
namespace System\Modules\Homepage\Controllers;

use System\Core\Load;

use Carbon\Carbon;

class Hello extends Load
{

	public function index_hmvc()
	{
		$arr = [
			'welcome' => Load::model('Model_Welcome')->welcome(), // Call my_name method from Model_Welcome
			'hmvc_page' => Load::model('Homepage\Model_Hello')->hmvc_page(), // Call mvc_page method from Model_Hello
			'date' => Carbon::now() // Instantiate today date with Carbon
		];

		// Load HMVC view page
		Load::template('header', $arr);
		Load::view('homepage', 'index', $arr);
		Load::template('footer', $arr);
	}

}
