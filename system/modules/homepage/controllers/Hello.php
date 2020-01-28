<?php
namespace System\Modules\Homepage\Controllers;

use System\Core\NSY_Controller;

use Carbon\Carbon;

class Hello extends NSY_Controller
{

	public function index_hmvc()
	{
		$arr = [
			'my_name' => $this->model('Model_Welcome', 'welcome'), // Call my_name method from Model_Welcome
			'hmvc_page' => $this->module('Homepage')->model('Model_Hello', 'hmvc_page'), // Call mvc_page method from Model_Hello
			'date' => Carbon::now() // Instantiate today date with Carbon
		];

		// Load HMVC view page
		$this->load_template('header', $arr)->load_view('homepage', 'index', $arr)->load_template('footer', $arr);
	}

}
