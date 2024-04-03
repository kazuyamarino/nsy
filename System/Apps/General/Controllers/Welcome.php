<?php

namespace System\Apps\General\Controllers;

use System\Core\Load;
use Carbon\Carbon;
use System\Apps\General\Models\Model_Welcome;

class Welcome extends Load
{

	private $Model_Welcome;

	public function __construct()
	{
		$this->Model_Welcome = new Model_Welcome;
	}

	public function index()
	{
		$arr = [
			'welcome' => $this->Model_Welcome->welcome(), // Call the welcome method from Model_Welcome
			'mvc_page' => $this->Model_Welcome->mvc_page(), // Call the mvc_page method from Model_Hello inside the Homepage module
			'date' => Carbon::now() // Instantiate today's date with Carbon
		];

		// Load MVC view page
		Load::template('Header', $arr);
		Load::view(null, 'Index', $arr);
		Load::template('Footer', $arr);
	}
}
