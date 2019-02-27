<?php
namespace Controllers;

defined('ROOT') OR exit('No direct script access allowed');

use Core\NSY_Controller;
use Models\Model_Welcome;

class Welcome extends NSY_Controller
{

	public function index()
	{
		$this->load_template("header")->load_view("index")->load_template("footer");
	}

}
