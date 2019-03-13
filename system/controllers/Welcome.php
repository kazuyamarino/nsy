<?php
namespace Controllers;

defined('ROOT') OR exit('No direct script access allowed');

use Core\NSY_Controller;
use Models\Model_Welcome;
use Carbon\Carbon;

class Welcome extends NSY_Controller
{

	public function index()
	{
		// Instantiate today date with Carbon
		$d['date'] = Carbon::now();

		// Passing variable into view
		$this->set($d);

		// Load view page
		$this->load_template("header")->load_view("index")->load_template("footer");
	}

}
