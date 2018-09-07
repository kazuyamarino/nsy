<?php

namespace Controllers;

use Core\NSY_Controller;
use Models\Model_Welcome;

defined('ROOT') OR exit('No direct script access allowed');

class Welcome extends NSY_Controller {

	public function index()
	{
  	$this->template("header");
    $this->view("index");
		$this->template("footer");
	}

}
