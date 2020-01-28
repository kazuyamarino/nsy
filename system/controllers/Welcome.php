<?php
namespace System\Controllers;

use System\Core\NSY_Controller;
use System\Core\Load;

use Carbon\Carbon;

class Welcome extends NSY_Controller
{

	public function index()
	{
		$arr = [
			'welcome' => $this->model('Model_Welcome', 'welcome'), // Call welcome method from Model_Welcome
			'mvc_page' => $this->module('Homepage')->model('Model_Hello', 'mvc_page'), // Call mvc_page method from Model_Hello
			'date' => Carbon::now() // Instantiate today date with Carbon
		];

		// Load MVC view page
		$this->load_template('header', $arr)->load_view(null, 'index', $arr)->load_template('footer', $arr);
	}

}
