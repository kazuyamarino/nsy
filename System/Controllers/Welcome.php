<?php

namespace System\Controllers;

use System\Core\NSY_Controller;
use System\Models\Model_Welcome;

defined('ROOT') OR exit('No direct script access allowed');

class Welcome extends NSY_Controller {

	public function index() {
		$this->template("header");
		$this->view("index");
		$this->template("footer");
	}

}
