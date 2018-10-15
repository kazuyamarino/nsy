<?php

namespace System\Modules\Homepage\Controllers;

use System\Core\NSY_Controller;
use System\Modules\Homepage\Models\Model_Hello;

defined('ROOT') OR exit('No direct script access allowed');

class Hello extends NSY_Controller {

	public function index_hmvc()
	{
    $this->template("header");
    $this->hmvc_view("index");
		$this->template("footer");
  }

}
