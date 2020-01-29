<?php
namespace System\Controllers;

use System\Core\NSY_Controller;

use Carbon\Carbon;

class Welcome extends NSY_Controller
{

	public function index()
	{
		$arr = [
			'mvc_page' => $this->model('Homepage\Model_Hello', 'mvc_page'), // Call mvc_page method from Model_Hello
			'date' => Carbon::now() // Instantiate today date with Carbon
		];

		// Load MVC view page
		$this->load_template('header', $arr)->load_view(null, 'index', $arr)->load_template('footer', $arr);
	}

}
