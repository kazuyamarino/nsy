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
		// instantiate the crud class
		$Model_Welcome = new Model_Welcome();

		// call the method get_data
		$d['data'] = $Model_Welcome->yourFunction();
		$d['date'] = Carbon::now();
		$this->set($d);

		//echo $d['data'];
		$this->load_template("header")->load_view("index")->load_template("footer");
	}

}
